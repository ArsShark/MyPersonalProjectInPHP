<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Str; 

class PostController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }


        public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:posts,title',
            'content' => 'required|min:50',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|exists:tags,id',
            'image' => 'nullable|image|max:2048',
            'files' => 'nullable|array',                          
            'files.*' => 'file|mimes:jpg,png,pdf,mp4|max:5120|no_spaces',  
        ]);

        $postData = $validated;
        unset($postData['tags'], $postData['files'], $postData['image']);

        $post = Post::create($postData + ['user_id' => User::first()->id]);

        $post->tags()->sync($request->tags ?? []);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->update(['image' => $path]);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (Str::startsWith($file->getMimeType(), 'image')) {
                    OptimizerChainFactory::create()->optimize($file->getRealPath());
                }
                $path = $file->store("posts/{$post->id}", 's3-fake'); 
                Media::create([
                    'post_id' => $post->id,
                    'path' => $path,
                    'type' => $file->getMimeType(),                 
                ]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Пост создан!');
    }

    public function show(Post $post)
    {
    $post->load(['category', 'tags', 'comments' => function ($q) {
        $q->with('user');
    }]);

    return view('posts.show', compact('post'));
    }
   public function index(Request $request)
    {
    $sort       = $request->query('sort', 'latest');
    $categoryId = $request->query('category');
    $search     = $request->query('search');

    $query = Post::with(['category', 'tags', 'comments'])
        ->withCount('comments');

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($search) {
        $query->where('title', 'LIKE', "%{$search}%");
    }

    switch ($sort) {
        case 'popular':
            $query->orderBy('comments_count', 'desc');
            break;
        default:
            $query->orderBy('created_at', 'desc');
    }
    $query->orderBy('id', 'desc');   
    $posts = $query->paginate(10)->withQueryString();
    if ($request->ajax()) {
    return view('posts.partials.cards', compact('posts'));
    }

    $categories = Category::orderBy('name')->get();
    $stats = [
    'posts'    => Post::count(),
    'comments' => Comment::count(),
    'topTag'   => Tag::withCount('posts')->orderBy('posts_count', 'desc')->first(),
            ];

    return view('posts.index', compact('posts', 'sort', 'categories', 'search', 'categoryId', 'stats'));
    }
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:posts,title,' . $post->id, 
            'content' => 'required|min:50',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|exists:tags,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $postData = $validated;
        unset($postData['tags']);
        
        $post->update($postData);

        $post->tags()->sync($request->tags ?? []);

        if ($request->boolean('remove_image')) {
            if ($post->image) {
                Storage::delete('public/' . $post->image);
                $post->update(['image' => null]);
            }
        } elseif ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $post->update(['image' => $path]);
        }

        return redirect()->route('posts.index')->with('success', 'Пост обновлен!');
    }

        public function destroy(Post $post)
        {
        if ($post->image) {
            Storage::delete('public/' . $post->image);
        }
        Storage::disk('s3-fake')->deleteDirectory("posts/{$post->id}");

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удалён!');
        }

        public function destroyFile(Media $media)
    {
        Storage::disk('s3-fake')->delete($media->path); 
        $media->delete();                               

        return back()->with('success', 'Файл удалён!');
    }

}