<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->setLocale('ru');
        
        Category::observe(CategoryObserver::class);

        Validator::extend('no_spaces', function ($attr, $value, $params) {
            
        return !preg_match('/\s/', $value->getClientOriginalName());
	});

    }
}
