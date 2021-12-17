<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Validator;
use Auth;
use App\Models\User;

class PostController extends Controller
{     
    public function index() {
        return view('posts.index');
    }
    public function getPosts(){
        $posts = Post::with('user')->orderBy('created_at', 'desc')->limit(20)->get();
        foreach ($posts as $post) {
            $post->like_count = Like::where('post_id', $post->id)->count();
            $post->comment_count = Comment::where('post_id', $post->id)->count();
            $post->self_like = Like::where('user_id', Auth::id())->where('post_id', $post->id)->exists();
            $post->self_post = Post::where('user_id', Auth::id())->where('id', $post->id)->exists();
            $post->isCommented = Comment::where('user_id', Auth::id())->where('post_id', $post->id)->exists();
        }
        return response()->json(['posts' => $posts]);
    }

    
    public function postProfile($id){
        $post = Post::with('user')->find($id);
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i='.strval($post->drink_id);
        $response = Http::get($url)->json();
        $drink = $response['drinks'][0];
        // count the number of like in the specific post
        $post->like_count = Like::where('post_id', $post->id)->count();

        // check if the user currently like the post
        $post->isLike = Like::where('post_id', $post->id)->where('user_id', Auth::id())->exists();

        // check if the user who currently logged in owns the post
        $post->selfPost = Post::where('id', $post->id)->where('user_id', Auth::id())->exists();
        
        $comments = Comment::with('user')->orderBy('created_at', 'desc')->where('post_id', $post->id)->paginate(4);
        foreach ($comments as $comment) {
            // check if the user who currently logged in owns the comment
            $comment->selfComment = Comment::where('id', $comment->id)->where('user_id', Auth::id())->exists();
        }
        $numOfComment = Comment::where('post_id', $post->id)->count();

        $percentageList = [];
        if ($numOfComment > 0) {
            $matchThese = ['post_id' => $id, 'classification' => 'Positive'];
            $numOfPositive = Comment::where($matchThese)->count();
            $matchThese = ['post_id' => $id, 'classification' => 'Neutral'];
            $numOfNeutral = Comment::where($matchThese)->count();
            $matchThese = ['post_id' => $id, 'classification' => 'Negative'];
            $numOfNegative = Comment::where($matchThese)->count();

            
            $positive = ($numOfPositive / $numOfComment) * 100; //percentage 
            $neutral = ($numOfNeutral / $numOfComment) * 100; //percentage 
            $negative = ($numOfNegative / $numOfComment) * 100; //percentage 

            array_push($percentageList, round($positive), round($neutral), round($negative));
        } else {
            $positive = 0;
            $neutral = 0;
            $negative = 0;
            array_push($percentageList, round($positive), round($neutral), round($negative));
        }   
        
    
        // get the ingredients and push it to ingredientsList
        $ingredientsList = [];
        for ($x = 0; $x <= 15; $x++) {
            $container = [];
            $id = $x + 1;
            $ingredient = 'strIngredient'. $id;
            $measurement = 'strMeasure'. $id;
            if ($drink[$ingredient] != null) {
                $img = 'https://www.thecocktaildb.com/images/ingredients/'.$drink[$ingredient].'-Medium.png';
                $ingredient = $drink[$ingredient];
                $measurement = $drink[$measurement];
                array_push($container, $img, $ingredient, $measurement);
                array_push($ingredientsList, $container);
            } else {
                break;
            }           
        }

        return view('posts.profile', ['response' => $response, 'post' => $post, 'comments' => $comments, 'percentage' => $percentageList, 'ingredients' => $ingredientsList]);
    }

    public function write($id) {
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i='.strval($id);
        $response = Http::get($url)->json();
        $drink = $response['drinks'];
        $user = User::where('id', Auth::id())->first();
        return view('posts.create', ['drink' => $drink],['user' => $user]);
    }

    public function store(Request $request) {
        $rules = array(
            'drinkId' => 'required',
            'title' => 'required',
            'description' => 'required',
            'classification' => 'required',
            'confidence' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $post = new Post;
        $post->user_id = Auth::id(); 
        $post->drink_id = $request->drinkId;
        $post->title = $request->title;
        $post->desc = $request->description;
        $post->classification = $request->classification;
        $post->confidence = $request->confidence;
        $post->save();

        return response()->json(['success' => 'Your comment has been successfuly submitted', 'post' => $post]);
    }

    public function edit($id) {
        $post = Post::find($id);

        $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i='.strval($post->drink_id);
        $response = Http::get($url)->json();
        $drink = $response['drinks'][0];

        return view('posts.edit', ['post' => $post, 'drink' => $drink]);

    }

    public function update(Request $request) {
        $rules = array(
            'postId' => 'required',
            'title' => 'required',
            'description' => 'required',
            'classification' => 'required',
            'confidence' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $post = Post::find($request->postId);
        $post->title = $request->title;
        $post->desc = $request->description;
        $post->classification = $request->classification;
        $post->confidence = $request->confidence;
        $post->save();

        return response()->json(['success' => 'Your post update has been successfuly submitted', 'post' => $post]);
    }

    public function destroy(Request $request){
        $rules = array(
            'post_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);
        
        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        // check if the user posted that specific post
        if (Post::where('id', $request->post_id)->where('user_id', Auth::id())->exists()) {
            $isOwned = true;
        } else {
            $isOwned = false;
        }

        if ($isOwned) {
            $post = post::where('id', $request->post_id);
            $post->delete();
            return response()->json(['success' => 'Post deleted successfuly']);
        } else {

        }
    }

}
