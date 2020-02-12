<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Area::creating(function($area){ // ? means if something does exist
          $prefix = $area->parent ? $area->parent->name . ' ' : ''; // : is otherwise
          $area->slug = str_slug($prefix . $area->name);
        });
        \App\Category::creating(function($category){ // ? means if something does exist
          $prefix = $category->parent ? $category->parent->name . ' ' : ''; // : is otherwise
          $category->slug = str_slug($prefix . $category->name);
        });
    }
}
