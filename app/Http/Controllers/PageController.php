<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', ['title' => 'Главная страница']);
    }

    public function about()
    {
        return view('pages.about', ['title' => 'О нас']);
    }

    public function contact()
    {
        return view('pages.contact', ['title' => 'Контакты']);
    }

    public function showPost(string $slug)
{
    $title = ucwords(str_replace('-', ' ', $slug));

    return view('pages.post', [
        'slug'  => $slug,
        'title' => $title,
    ]);
}

}