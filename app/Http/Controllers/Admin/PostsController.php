<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $posts = Post::all();
        return view( 'admin.posts.index', compact( 'posts' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categories = Category::pluck( 'title', 'id' )->all();
        $tags = Tag::pluck( 'title', 'id' )->all();
        return view( 'admin.posts.create', compact( 'categories', 'tags' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store( Request $request ) {
        $this->validate( $request, [
            'title' => 'required',
            'content' => 'required',
            'date' => 'required',
            'image' => 'nullable|image',
        ] );

        $post = Post::add( $request->all() );
        $post->uploadImage( $request->file( 'image' ) );
        $post->setCategory( $request->get( 'category_id' ) );
        $post->setTags( $request->get( 'tags' ) );
        $post->toogleStatus( $request->get( 'status' ) );
        $post->toogleFeatured( $request->get( 'is_featured' ) );

        return redirect()->route( 'posts.index' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id ) {
        $post = Post::find( $id );
        $categories = Category::pluck( 'title', 'id' )->all();
        $tags = Tag::pluck( 'title', 'id' )->all();
        $selectedTags = $post->tags->pluck('id')->all();

        return view( 'admin.posts.edit', compact(
            'categories',
            'tags',
            'post',
            'selectedTags'
        ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update( Request $request, $id ) {
        $this->validate( $request, [
            'title' => 'required',
            'content' => 'required',
            'date' => 'required',
            'image' => 'nullable|image',
        ] );

        $post = Post::find($id);
        $post->edit($request->all());
        $post->uploadImage( $request->file( 'image' ) );
        $post->setCategory( $request->get( 'category_id' ) );
        $post->setTags( $request->get( 'tags' ) );
        $post->toogleStatus( $request->get( 'status' ) );
        $post->toogleFeatured( $request->get( 'is_featured' ) );

        return redirect()->route( 'posts.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id ) {
        Post::find($id)->remove();
        return redirect()->route('posts.index');
    }
}
