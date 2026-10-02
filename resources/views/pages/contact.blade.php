@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Свяжитесь с нами</h1>

        <x-form action="/contact" method="POST" button-text="Отправить сообщение">
            
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-gray-700">Сообщение</label>
                <textarea name="message" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="4"></textarea>
            </div>
        </x-form>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', (e) => {
            const email = document.querySelector('input[type="email"]');
            const message = document.querySelector('textarea');

            if (!email.value.includes('@')) {
                e.preventDefault();
                alert('Введите корректный email!');
                return;
            }

            if (message.value.trim().length < 10) {
                e.preventDefault();
                alert('Сообщение должно содержать минимум 10 символов!');
            }
        });
    </script>
@endsection