<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller {

  public function index() {
    $comments = Comment::all();

    return view('admin.comments.index', compact('comments'));
  }

  public function toogle($id) {
    $comment = Comment::find($id);
    $comment->toogleStatus();

    return redirect()->back();
  }

  public function destroy( $id ) {
    Comment::find($id)->remove();
    return redirect()->back();
  }

}
