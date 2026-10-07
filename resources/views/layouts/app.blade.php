<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Блог' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Навигационное меню -->
    <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ isOpen: false }">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Мой Блог</a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-800">О нас</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-800">Контакты</a>
                <a href="{{ route('posts.create') }}" class="text-gray-600 hover:text-gray-800">Новый пост</a>
                <a href="{{ route('posts.index') }}"
                class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('posts.index') ? 'text-blue-600' : '' }}">
                Все посты
                </a>
            </div>
            <div class="md:hidden flex items-center">
                <button @click="isOpen = !isOpen" class="p-2 text-gray-600 hover:text-gray-800" aria-label="Открыть меню">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="isOpen" @click.outside="isOpen = false" class="md:hidden pb-4 space-y-2">
            <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md">О нас</a>
            <a href="{{ route('contact') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md">Контакты</a>
            <a href="{{ route('posts.index') }}"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
             Все посты
            </a>
        </div>
    </div>
    </nav>

    <!-- Контент страницы -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-md p-4">
            {{ session('success') }}
            </div>
        </div>
        @endif
        @yield('content')
    </div>
</body>
</html>