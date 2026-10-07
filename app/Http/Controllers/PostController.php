<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Comment;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create', [
            'title' => 'Новый пост',
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => User::first()->id,
        ]);

        $tagIds = [];
        foreach (explode(',', $request->tags) as $tagName) {
            $tagName = trim($tagName);

            if ($tagName === '') {
                continue;
            }

            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );

            $tagIds[$tag->id] = $tag->id;
        }

        $post->tags()->attach(array_values($tagIds));

        return redirect()
            ->route('home')
            ->with('success', 'Пост успешно создан!');
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
}