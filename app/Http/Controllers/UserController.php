<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class UserController extends Controller
{
    public function userProfile($id) {
        //get the information of the user
        $user = User::where('id', $id)->first();
        //get the user posts
        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(2);
        
        //get the user comments
        $comments = Comment::with('user')->orderBy('created_at', 'desc')->where('user_id', $id)->paginate(6);

        // loop the comments to check if the user who logged in owns the comment
        foreach ($comments as $comment) {
            $comment->selfComment = Comment::where('id', $comment->id)->where('user_id', Auth::id())->exists();
            $comments->confidence = round($comment->confidence * 100);
        }

        //instantiate variable for the number of like and comments received overall by the user
        $likeReceived = 0;
        $commentReceived = 0;

        // add additional fields to post (number of likes, and comments, isLike, isCommented)
        foreach ($posts as $post) {
            // convert the confidence level into percentage
            $post->confidence = round($post->confidence * 100);

            //get the number of likes
            $post->likeCount = Like::where('post_id', $post->id)->count(); 
            $likeReceived += $post->likeCount;
            //get the number of comments
            $post->commentCount = Comment::where('post_id', $post->id)->count();
            $commentReceived += $post->commentCount;
            //check if the user who logged in already liked the post
            $post->isLike = Like::where('post_id', $post->id)->where('user_id', Auth::id())->exists();
            //check if the user who logged in already commented the post
            $post->isCommented = Comment::where('post_id', $post->id)->where('user_id', Auth::id())->exists();
            //check if the user who logged in owns the posts
            $post->selfPost = Post::where('id', $post->id)->where('user_id', Auth::id())->exists();
        }

        // create new fields for user model to pass the value of the total likes and comments received
        $user->likeReceived = $likeReceived;
        $user->commentReceived = $commentReceived;

        // create new field for the number of posts of specifc user
        $user->postCount = Post::where('user_id', $id)->count();

        // get top 3 favorite drink id topic of the user profile
        $faveTopic = Post::select('drink_id')
            ->selectRaw('COUNT(*) AS count')
            ->groupBy('drink_id')
            ->orderByDesc('count')
            ->where('user_id', $user->id)
            ->limit(3)
            ->get();

        return view('users.profile', ['user' => $user, 'posts' => $posts, 'comments' => $comments]);
    }

    public function edit() {
        $user = User::where('id', Auth::id())->first();
        return view('users.edit', ['user' => $user]);
    }

    public function analysis() {
        $user = User::where('id', Auth::id())->first();
        $user->postPositiveCount = Post::where('classification', 'Positive')->where('user_id', Auth::id())->count();
        $user->postNeutralCount = Post::where('classification', 'Neutral')->where('user_id', Auth::id())->count();
        $user->postNegativeCount = Post::where('classification', 'Negative')->where('user_id', Auth::id())->count();
        $user->postCount = Post::where('user_id', Auth::id())->count();
        $user->commentPositiveCount = Comment::where('classification', 'Positive')->where('user_id', Auth::id())->count();
        $user->commentNeutralCount = Comment::where('classification', 'Neutral')->where('user_id', Auth::id())->count();
        $user->commentNegativeCount = Comment::where('classification', 'Negative')->where('user_id', Auth::id())->count();
        $user->commentCount = Comment::where('user_id', Auth::id())->count();

        $user->totalpc= $user->postCount + $user->commentCount;
        $user->totalpositive= $user->postPositiveCount + $user->commentPositiveCount;
        $user->totalneutral= $user->postNeutralCount + $user->commentNeutralCount;
        $user->totalnegative= $user->postNegativeCount + $user->commentNegativeCount;
        
        $user->postpositivepercent= ($user->postPositiveCount / $user->postCount) * 100;
        $user->postneutralpercent= ($user->postNeutralCount / $user->postCount) * 100;
        $user->postnegativepercent= ($user->postNegativeCount / $user->postCount) * 100;

        $user->commentpositivepercent= ($user->commentPositiveCount / $user->commentCount) * 100;
        $user->commentneutralpercent= ($user->commentNeutralCount / $user->commentCount) * 100;
        $user->commentnegativepercent= ($user->commentNegativeCount / $user->commentCount) * 100;

        $user->totalpositivepercent= ($user->totalpositive / $user->totalpc) * 100;
        $user->totalnegativepercent= ($user->totalnegative / $user->totalpc) * 100;
        $user->totalneutralpercent= ($user->totalneutral / $user->totalpc) * 100;
        return view('users.analysis', ['user' => $user]);
    }

   public function update(Request $req){

    $data=User::find($req->id);
    $data->title=$req->title;
    $data->name=$req->firstName;
    $data->lastname=$req->lastName;
    $data->email=$req->email;
    $data->city=$req->city;
    $data->street_name=$req->state;
    $data->country=$req->country;
    $data->latitude=$req->lat;
    $data->longitude=$req->lng;
    $data->save();
    return redirect('/');
  }

   public function Pass() {
        $user = User::where('id', Auth::id())->first();
        return view('users.pass', ['user' => $user]);
    }

  public function changePass(Request $req){
    $req->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);
    $data=User::find($req->id);
    $data->password=Hash::make($req->password);
    $data->save();
    return redirect('/');
  }

}
