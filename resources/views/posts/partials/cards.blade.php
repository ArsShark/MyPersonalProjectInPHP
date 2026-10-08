@forelse ($posts as $post)
    <div data-post-card class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        @if ($post->image)
            <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-48 object-cover">
        @endif

        <div class="p-6">
            <div class="flex items-center flex-wrap gap-x-2 gap-y-1 mb-2 text-sm">
                <span class="text-gray-600">
                    {{ $post->created_at->format('d.m.Y') }}
                </span>

                <span class="text-gray-400">•</span>

                <span class="text-blue-600">
                    {{ $post->category?->name ?? 'Без категории' }}
                </span>

                <span class="text-gray-400">•</span>

                <span class="text-gray-600">
                    💬 {{ $post->comments_count }}
                </span>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 mb-2">
                {{ $post->title }}
            </h2>

            <p class="text-gray-600 mb-4">
                {{ Str::limit($post->content ?? '', 150) }}
            </p>

            <div class="flex items-center justify-between gap-3">
                <div class="flex flex-wrap gap-1">
                    @forelse ($post->tags as $tag)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                            {{ $tag->name }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-400">
                            Без тегов
                        </span>
                    @endforelse
                </div>

                <a href="{{ route('posts.show', $post) }}"
                   class="text-blue-600 hover:text-blue-800 whitespace-nowrap">
                    Читать →
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('posts.edit', $post) }}"
                   class="text-sm text-yellow-600 hover:text-yellow-700 hover:underline">
                    Редактировать
                </a>

                <form action="{{ route('posts.destroy', $post) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Точно удалить пост?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="text-sm text-red-600 hover:text-red-700 hover:underline">
                        Удалить
                    </button>
                </form>
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