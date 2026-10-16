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
    {{-- Лаба 6: галерея прикреплённых файлов (диск s3-fake) --}}
    @if ($post->media->isNotEmpty())
    <section class="mt-8">
    <h3 class="text-xl font-semibold text-gray-900 mb-4">
        Прикреплённые файлы ({{ $post->media->count() }})
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($post->media as $media)
            <div class="relative border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                @if (Str::startsWith($media->type, 'image'))
                    {{-- Картинки: превью на весь тайл --}}
                    <img src="{{ Storage::disk('s3-fake')->url($media->path) }}"
                         alt="Прикреплённый файл"
                         class="w-full h-48 object-cover">
                @else
                    {{-- Всё остальное (pdf, mp4...): плитка со ссылкой --}}
                    <div class="h-48 flex flex-col items-center justify-center p-4 bg-gray-50 text-center">
                        <span class="text-3xl mb-2">
                            @if (Str::startsWith($media->type, 'video'))🎬
                            @elseif ($media->type === 'application/pdf')📄
                            @else📦@endif
                        </span>
                        <a href="{{ Storage::disk('s3-fake')->url($media->path) }}" target="_blank"
                           class="text-sm text-blue-600 hover:underline break-all">
                            {{ basename($media->path) }}
                        </a>
                        <span class="text-xs text-gray-500 mt-1">{{ $media->type }}</span>
                    </div>
                @endif

                {{-- Кнопка удаления файла (маршрут posts.deleteFile из партии 2) --}}
                <form method="POST" action="{{ route('posts.deleteFile', $media) }}"
                      class="absolute top-2 right-2"
                      onsubmit="return confirm('Удалить файл?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded-full font-bold shadow"
                            title="Удалить файл">
                        ×
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    </section>
    @endif
    
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