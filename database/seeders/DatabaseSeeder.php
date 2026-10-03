<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory(5)->create();

        Tag::factory(10)->create();

        foreach (range(1, 20) as $i) {
    Post::factory()
        ->for(Category::inRandomOrder()->first())
        ->has(Comment::factory(3))
        ->create()
        ->tags()
        ->attach(Tag::inRandomOrder()->take(3)->pluck('id'));
        }
    }
}