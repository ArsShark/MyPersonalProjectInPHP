@props(['post' => null, 'categories', 'tags'])

<form method="POST" 
      action="{{ $post ? route('posts.update', $post) : route('posts.store') }}" 
      enctype="multipart/form-data" 
      class="space-y-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    @csrf
    @if($post) @method('PUT') @endif

    <!-- Блок ошибок -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
            <div class="font-bold mb-2">Ошибки валидации:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Заголовок -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Заголовок *</label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" 
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
        @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <!-- Категория -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Категория *</label>
        <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">-- Выберите категорию --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Теги -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Теги</label>
        <select name="tags[]" multiple class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" size="5">
            @php
                $selectedTags = old('tags', $post ? $post->tags->pluck('id')->toArray() : []);
            @endphp
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Зажмите Ctrl (Cmd на Mac) для выбора нескольких тегов.</p>
    </div>

    <!-- Изображение -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Изображение</label>
        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        
        @if ($post && $post->image)
            <div class="mt-4 flex items-start space-x-4">
                <img src="{{ asset('storage/' . $post->image) }}" class="w-32 h-32 object-cover rounded-md border border-gray-200">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 mb-2">Текущее изображение</span>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remove_image" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="text-red-600 text-sm font-medium hover:underline">Удалить изображение</span>
                    </label>
                </div>
            </div>
        @endif
    </div>

    <!-- Контент -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Содержание * (мин. 50 символов)</label>
        <textarea name="content" rows="8" id="content-editor"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

        <div class="mt-4">
            <button type="button" id="preview-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition">
                👁 Предпросмотр текста
            </button>
            <div id="preview-box" class="hidden mt-3 p-4 bg-gray-50 rounded-md border border-gray-200 min-h-[100px] text-gray-800 whitespace-pre-wrap"></div>
        </div>
    </div>

    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
        <a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Отмена</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md shadow-sm transition">
            {{ $post ? '💾 Обновить пост' : '🚀 Создать пост' }}
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('preview-btn');
        const box = document.getElementById('preview-box');
        const editor = document.getElementById('content-editor');

        btn.addEventListener('click', function() {
            if (box.classList.contains('hidden')) {
                box.innerText = editor.value || 'Текст пуст...';
                box.classList.remove('hidden');
                btn.innerText = '🙈 Скрыть предпросмотр';
            } else {
                box.classList.add('hidden');
                btn.innerText = '👁 Предпросмотр текста';
            }
        });
    });
</script>