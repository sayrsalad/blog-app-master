<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Auth;
class CommentController extends Controller
{
    
    public function postComments($id) {
        $comments = Comment::with('user')->where('post_id', $id)->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment) {
            $comment->self_comment = Comment::where('user_id', Auth::id())->where('id', $comment->id)->where('post_id', $id)->exists();
        }
        
        $comment_count = Comment::where('post_id', $id)->count();
        return response()->json(['comments' => $comments, 'comment_count' => $comment_count]);
    }

    public function comments(){
        $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->like_count = Like::where('post_id', $post->id)->count();
        }

        $comments = Comment::with('user')->orderBy('created_at', 'desc')->limit(6)->get();
        return view('comments.index', ['posts' => $posts, 'comments' => $comments]);
    }

    public function store(Request $request) {
        $rules = array(
            'postId' => 'required',
            'comment' => 'required',
            'classification' => 'required',
            'confidence' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $comment = new Comment;
        $comment->user_id = Auth::id(); 
        $comment->post_id = $request->postId;
        $comment->comment = $request->comment;
        $comment->classification = $request->classification;
        $comment->confidence = $request->confidence;
        $comment->save();

        $comment_count = Comment::where('post_id', $request->postId)->count(); // comment count
        return response()->json(['success' => 'Your comment has been successfuly submitted', 'comment' => $comment, 'comment_count' => $comment_count]);
    }

    public function edit($id) {
        $comment = Comment::find($id);
        return response()->json(['comment' => $comment]);
    }

    public function update(Request $request) {
        $rules = array(
            'commentId' => 'required',
            'comment' => 'required',
            'classification' => 'required',
            'confidence' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        // Find
        $comment = Comment::find($request->commentId);
        // Change the fields
        $comment->comment = $request->comment;
        $comment->comment = $request->comment;
        $comment->classification = $request->classification;
        $comment->confidence = $request->confidence;
        // Save
        $comment->save();

        return response()->json(['success' => 'Your comment has been successfuly updated']);
    }

    public function destroy(Request $request){
        $rules = array(
            'comment_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);
        
        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        // check if the user posted that specific post
        if (Comment::where('id', $request->comment_id)->where('user_id', Auth::id())->exists()) {
            $isOwned = true;
        } else {
            $isOwned = false;
        }

        if ($isOwned) {
            $comment = Comment::where('id', $request->comment_id);
            $comment->delete();
            return response()->json(['success' => 'Post deleted successfuly']);
        } else {

        }
    }
}
