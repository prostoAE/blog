<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/post/{slug}', [HomeController::class, 'show'])->name('post.show');
Route::get('/tag/{slug}', [HomeController::class, 'tag'])->name('tag.show');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/author/{id}', [HomeController::class, 'author'])->name('author.show');
Route::post('/subscribe', [SubsController::class, 'subscribe']);
Route::get('/verify/{token}', [SubsController::class, 'verify']);

Route::group(['middleware' => 'auth'], function () {
  Route::get('/profile', [ProfileController::class, 'index']);
  Route::post('/profile', [ProfileController::class, 'store']);
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::post('/comment', [CommentsController::class, 'store']);
});

Route::group(['middleware' => 'guest'], function () {
  Route::get('/register', [AuthController::class, 'registerForm']);
  Route::post('/register', [AuthController::class, 'register']);
  Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('/categories', CategoriesController::class);
    Route::resource('/tags', TagsController::class);
    Route::resource('/users', UsersController::class);
    Route::resource('/posts', PostsController::class);
    Route::get('/comments', [\App\Http\Controllers\Admin\CommentsController::class, 'index'])->name('admin.comments');
    Route::get('/comments/toogle/{id}', [\App\Http\Controllers\Admin\CommentsController::class, 'toogle']);
    Route::delete('/comments/{id}/destroy', [\App\Http\Controllers\Admin\CommentsController::class, 'destroy'])->name('comments.destroy');
    Route::resource('/subscribers', SubscribersController::class);
});
