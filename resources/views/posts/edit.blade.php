@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Редактирование поста</h1>
        <p class="text-gray-600 mt-1">Внесите изменения в статью «{{ $post->title }}».</p>
    </div>
    
    @include('posts.partials.form', ['post' => $post, 'categories' => $categories, 'tags' => $tags])
</div>
@endsection