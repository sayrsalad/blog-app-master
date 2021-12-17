<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Validator;
use Auth;


class LikeController extends Controller
{
    //
    public function postLikes($id) {

        $likes = Like::with('user')->where('post_id', $id)->orderBy('created_at', 'desc')->get();
        $like_count = Like::with('user')->where('post_id', $id)->count();
        foreach ($likes as $like) {
            $like->self_like = Like::where('id', $like->id)->where('user_id', Auth::id())->where('post_id', $id)->exists();
        }

        return response()->json(['likes' => $likes, 'like_count' => $like_count]);
    }

    public function likePost(Request $request) {
        $rules = array(
            'post_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //check if the user already like the post
        if (Like::where('post_id', $request->post_id)->where('user_id', Auth::id())->exists()) {
            $isLike = true;
        } else {
            $isLike = false;
        }

        // if the user like the post
        if ($isLike) { //delete the liked post of the user
            $likes = Like::where('user_id', Auth::id())->where('post_id', $request->post_id);
            $likes->delete();
            $isLike = false; 
            $like_count = Like::where('post_id', $request->post_id)->count();
            return response()->json(['success' => 'Post unliked', 'isLike' => $isLike, 'likeCount' => $like_count]);
        } else {
            $like = new Like;
            $like->user_id = Auth::id(); 
            $like->post_id = $request->post_id;
            $like->save();
            $isLike = true; 
            $like_count = Like::where('post_id', $request->post_id)->count();
            return response()->json(['success' => 'Post liked', 'isLike' => $isLike, 'likeCount' => $like_count]);
        }



    }
}
