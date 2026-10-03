@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Новый пост</h1>

        <x-form action="{{ route('posts.store') }}" method="POST" button-text="Опубликовать">
            <div>
                <label class="block text-gray-700">Заголовок</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-gray-700">Категория</label>
                <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Теги (через запятую)</label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                       placeholder="php, laravel, учеба"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-gray-700">Содержимое</label>
                <textarea name="content" rows="6"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content') }}</textarea>
            </div>
        </x-form>

        @if ($errors->any())
            <div class="mt-4 bg-red-100 border border-red-300 text-red-800 rounded-md p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection