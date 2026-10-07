@forelse ($posts as $post)
    <div data-post-card class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        @if ($post->image)
            <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-48 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-center mb-2">
                <span class="text-sm text-gray-600">
                    {{ $post->created_at->format('d.m.Y') }}
                </span>
                <span class="mx-2">•</span>
                <span class="text-sm text-blue-600">
                    {{ $post->category?->name ?? 'Без категории' }}
                </span>
                <span class="mx-2">•</span>
                <span class="text-sm text-gray-600">
                    💬 {{ $post->comments_count }}
                </span>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                {{ $post->title }}
            </h2>
            <p class="text-gray-600 mb-4">{{ Str::limit($post->text, 150) }}</p>
            <div class="flex items-center justify-between">
                <div class="flex flex-wrap gap-1">
                    @foreach ($post->tags as $tag)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                <a href="{{ route('posts.show', $post) }}"
                   class="text-blue-600 hover:text-blue-800 whitespace-nowrap ml-2">
                    Читать →
                </a>
            </div>
        </div>
    </div>
@empty
    <p class="text-gray-600 col-span-full text-center py-12">
        Ничего не найдено.
        <a href="{{ route('posts.index') }}" class="text-blue-600 hover:underline">Сбросить фильтры</a>
    </p>
@endforelse

<div class="hidden" data-infinite-more="{{ $posts->hasMorePages() ? '1' : '0' }}"></div>