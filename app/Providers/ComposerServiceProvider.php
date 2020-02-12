<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;/////
use App\Http\ViewComposers\AreaComposer;
use App\Http\ViewComposers\NavigationComposer;/////

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AreaComposer::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', AreaComposer::class);//share our area with all of our views
        View::composer('layouts.partials._navigation', NavigationComposer::class);

        View::composer(['listings.partials.forms._categories'], function ($view) {
            $categories = \App\Category::get()->toTree(); //takes all categories and put them into an iterable tree

            $view->with(compact('categories'));
        });

        View::composer(['listings.partials.forms._areas'], function ($view) {
            $areas = \App\Area::get()->toTree();

            $view->with(compact('areas'));
        });
    }
}
