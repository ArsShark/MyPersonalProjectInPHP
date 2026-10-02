@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="mt-2 text-sm text-gray-500">Slug статьи: {{ $slug }}</p>
    </div>
@endsection