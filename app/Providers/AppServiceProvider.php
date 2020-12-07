<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register() {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot() {
    View::composer('pages._sidebar', function ($view) {
      $view->with('popularPosts', Post::getPopularPosts()); //пример с выносом статического метода в модель
      $view->with('featuredPosts', Post::where( 'is_featured', 1 )->take( 3 )->get());
      $view->with('recentPosts', Post::orderBy( 'date', 'desc' )->take( 4 )->get());
      $view->with('categories', Category::all());
    });

    Paginator::useBootstrap();
  }
}
