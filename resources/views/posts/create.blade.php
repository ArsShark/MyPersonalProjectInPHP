@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Создание нового поста</h1>
        <p class="text-gray-600 mt-1">Заполните форму ниже, чтобы опубликовать статью.</p>
    </div>
    
    @include('posts.partials.form', ['post' => null, 'categories' => $categories, 'tags' => $tags])
</div>
@endsection