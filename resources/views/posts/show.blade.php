@extends('layouts.app')

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('posts.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
        ← Назад к списку
    </a>

    @if ($post->image)
        <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
             alt="{{ $post->title }}"
             class="w-full h-96 object-cover rounded-lg mb-6">
    @endif

    <div class="flex items-center text-sm text-gray-600 mb-4">
        <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
        <span class="mx-2">•</span>
        <span class="text-blue-600">{{ $post->category?->name ?? 'Без категории' }}</span>
        <span class="mx-2">•</span>
        <span>{{ $post->comments->count() }} комм.</span>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

    <div class="prose max-w-none text-gray-700 mb-8">
        {!! nl2br(e($post->content)) !!}
    </div>

    <div class="flex flex-wrap gap-2 mb-8">
        @foreach ($post->tags as $tag)
            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                {{ $tag->name }}
            </span>
        @endforeach
    </div>

    <section class="border-t pt-8">
        <h2 class="text-2xl font-semibold mb-4">Комментарии ({{ $post->comments->count() }})</h2>
        @forelse ($post->comments as $comment)
            <div class="bg-gray-50 p-4 rounded-lg mb-3">
                <p class="text-gray-800">{{ $comment->text }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500">Комментариев пока нет.</p>
        @endforelse
    </section>
</article>
@endsection