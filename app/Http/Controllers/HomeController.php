<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;


class HomeController extends Controller {

  public function index() {
    $posts = Post::paginate( 2 );

    return view( 'pages.index', compact('posts'));
  }

  public function show( $slug ) {
    $post = Post::where( 'slug', $slug )->firstOrFail();


    return view( 'pages.show', compact( 'post' ) );
  }

  public function tag( $slug ) {
    $tag = Tag::where( 'slug', $slug )->firstOrFail();
    $posts = $tag->posts()->where( 'status', 1 )->paginate( 2 );

    return view( 'pages.list', compact( 'posts' ) );
  }

  public function category( $slug ) {
    $category = Category::where( 'slug', $slug )->firstOrFail();
    $posts = $category->posts()->where( 'status', 1 )->paginate( 2 );;

    return view( 'pages.list', compact( 'posts' ) );
  }

  public function author( $id ) {
    $user = User::find($id);
    $posts = $user->posts()->where('user_id', $user->id)->paginate(2);

    return view( 'pages.list', compact( 'posts' ) );
  }
}
