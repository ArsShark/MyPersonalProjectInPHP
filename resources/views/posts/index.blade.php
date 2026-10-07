@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:flex lg:items-start lg:gap-8">
    <div class="flex-1 min-w-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Все посты</h1>

        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex space-x-4">
                <a href="{{ route('posts.index', array_filter(['sort' => 'latest', 'category' => $categoryId, 'search' => $search])) }}"
                   class="px-4 py-2 rounded-md {{ $sort === 'latest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Новые
                </a>
                <a href="{{ route('posts.index', array_filter(['sort' => 'popular', 'category' => $categoryId, 'search' => $search])) }}"
                   class="px-4 py-2 rounded-md {{ $sort === 'popular' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Популярные
                </a>
            </div>
            <form method="GET" action="{{ route('posts.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <select name="category" onchange="this.form.submit()"
                        class="border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Все категории</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="search" name="search" value="{{ $search }}" placeholder="Поиск по заголовку…"
                       class="border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                    Найти
                </button>
            </form>
        </div>

        <div id="posts-grid" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @include('posts.partials.cards')
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>

        <div id="loading" class="hidden text-center py-4 text-gray-500">Загрузка…</div>

        <script>
            (() => {
                const grid = document.getElementById('posts-grid');
                const loading = document.getElementById('loading');
                const params = new URLSearchParams(window.location.search);
                let page = parseInt(params.get('page') ?? '1', 10);
                let busy = false;   
                let done = false;   

                const loadMore = async () => {
                    if (busy || done || !grid) return;
                    busy = true;
                    loading.classList.remove('hidden');
                    page += 1;

                    const url = new URL(window.location.href);
                    url.searchParams.set('page', page);

                    try {
                        const response = await axios.get(url.toString(), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        });

                        const tmp = document.createElement('div');
                        tmp.innerHTML = response.data;
                        const more = tmp.querySelector('[data-infinite-more]')?.dataset.infiniteMore === '1';
                        const hasCards = tmp.querySelector('[data-post-card]') !== null;

                        grid.querySelectorAll('[data-infinite-more]').forEach(el => el.remove());

                        if (hasCards) {
                            grid.insertAdjacentHTML('beforeend', response.data);
                        }

                        if (!more || !hasCards) {
                            done = true;
                            loading.textContent = 'Это все посты';
                        }
                    } catch (e) {
                        page -= 1;                 
                        loading.textContent = 'Ошибка загрузки';
                        done = true;
                    } finally {
                        busy = false;
                        if (!done) loading.classList.add('hidden');
                    }
                };

                const handleScroll = () => {
                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                        loadMore();
                    }
                };

                window.addEventListener('scroll', handleScroll);
            })();
        </script>
    </div>

    <aside class="w-full lg:w-72 shrink-0 mt-8 lg:mt-0">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold mb-4">Статистика блога</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li>Всего постов: <span class="font-medium">{{ $stats['posts'] }}</span></li>
                <li>Всего комментариев: <span class="font-medium">{{ $stats['comments'] }}</span></li>
                <li>Популярный тег: <span class="font-medium">{{ $stats['topTag']?->name ?? '—' }}</span></li>
            </ul>
        </div>
    </aside>
</div>
@endsection