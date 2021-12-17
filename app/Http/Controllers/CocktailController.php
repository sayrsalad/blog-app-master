<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\Like;
use Auth;

class CocktailController extends Controller
{
    public function index(){
        return view('cocktails.index');
    }

    public function chooseTopic() {
        return view('posts.choose-topic');
    }

    // function for getting the information using the id
    public function cocktailProfile($id){
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i='.strval($id);
        $response = Http::get($url)->json();
        $drink = $response['drinks'][0];

        $posts = Post::with('user')
            ->where('drink_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        foreach ($posts as $post) {
            $post->like_count = Like::where('post_id', $post->id)->count();
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
        
        return view('cocktails.profile', ['drink' => $drink, 'posts' => $posts, 'ingredients' => $ingredientsList]);
    }

    public function getTopics() {
        // get top 3 favorite drink id topic of the user profile
        $topics = Post::select('drink_id')
            ->selectRaw('COUNT(*) AS topic_count')
            ->groupBy('drink_id')
            ->orderByDesc('topic_count')
            ->get();

        foreach ($topics as $topic) {
            $count = Post::where('drink_id', $topic->drink_id)->count(); //get the topic count per drink
            $positive = Post::where('drink_id', $topic->drink_id)->where('classification', 'Positive')->count(); //get the total positive count
            $neutral =  Post::where('drink_id', $topic->drink_id)->where('classification', 'Neutral')->count(); //get the total neutral count
            $negative = Post::where('drink_id', $topic->drink_id)->where('classification', 'Negative')->count(); //get the total negative count

            $topic->post_count = $count; //get the topic count per drink
            $topic->positive_count = round(($positive / $count) * 100, 0); 
            $topic->neutral_count = round(($neutral / $count) * 100, 0);  
            $topic->negative_count = round(($negative / $count) * 100, 0); 
        }

        return response()->json(['topics' => $topics]);
    }

    public function getRandom() {
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/random.php';
        // $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=11014';
        $response = Http::get($url)->json();
        $drink = $response['drinks'][0];
        
        //check if there is an existing post related to the drink_id
        $drink_id = $drink['idDrink'];
        
        // the drink id exist in Post table
        if (Post::where('drink_id', $drink_id)->exists()) {
            $count = Post::where('drink_id', $drink_id)->count(); //get the topic count per drink
            $positive = Post::where('drink_id', $drink_id)->where('classification', 'Positive')->count(); //get the total positive count
            $neutral =  Post::where('drink_id', $drink_id)->where('classification', 'Neutral')->count(); //get the total neutral count
            $negative = Post::where('drink_id', $drink_id)->where('classification', 'Negative')->count(); //get the total negative count

            $drink['postCount'] = $count;
            $drink['positive'] = round(($positive / $count) * 100, 0); 
            $drink['neutral'] = round(($neutral / $count) * 100, 0); 
            $drink['negative'] = round(($negative / $count) * 100, 0); 
        } else {
            $drink['postCount'] = 0;
        }

        return response()->json(['drink' => $drink]);
    }

    public function searchByID($id) {
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i='.strval($id);
        $response = Http::get($url)->json();
        
        if ($response['drinks'] != null) {
            $drink = $response['drinks'][0];
            //check if there is an existing post related to the drink_id
            $drink_id = $drink['idDrink'];
            
            // the drink id exist in Post table
            if (Post::where('drink_id', $drink_id)->exists()) {
                $count = Post::where('drink_id', $drink_id)->count(); //get the topic count per drink
                $positive = Post::where('drink_id', $drink_id)->where('classification', 'Positive')->count(); //get the total positive count
                $neutral =  Post::where('drink_id', $drink_id)->where('classification', 'Neutral')->count(); //get the total neutral count
                $negative = Post::where('drink_id', $drink_id)->where('classification', 'Negative')->count(); //get the total negative count

                $drink['postCount'] = $count;
                $drink['positive'] = round(($positive / $count) * 100, 0); 
                $drink['neutral'] = round(($neutral / $count) * 100, 0); 
                $drink['negative'] = round(($negative / $count) * 100, 0); 
            } else {
                $drink['postCount'] = 0;
            }

            return response()->json(['drink' => $drink]);

        } else {
            return response()->json(['drink' => $response['drinks']]);
        }   
    }

    public function searchByFirstLetter($letter) {
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/search.php?f='.$letter;
        $response = Http::get($url)->json();
        $drinks = $response['drinks'];
        $drinks = json_decode(json_encode($drinks));

        foreach ($drinks as $drink) {
            $id = $drink->idDrink;

            if (Post::where('drink_id', $id)->exists()) {
                $count = Post::where('drink_id', $id)->count(); //get the topic count per drink
                $positive = Post::where('drink_id', $id)->where('classification', 'Positive')->count(); //get the total positive count
                $neutral =  Post::where('drink_id', $id)->where('classification', 'Neutral')->count(); //get the total neutral count
                $negative = Post::where('drink_id', $id)->where('classification', 'Negative')->count(); //get the total negative count

                $drink->postCount = $count;
                $drink->positive = round(($positive / $count) * 100, 0); 
                $drink->neutral = round(($neutral / $count) * 100, 0); 
                $drink->negative = round(($negative / $count) * 100, 0); 
            } else {
                $drink->postCount = 0;
            }
        }

        // sort the object by number of post
        usort($drinks, fn($a, $b) => $b->postCount - $a->postCount);

        return response()->json(['drinks' => $drinks]);
    } 
    
    public function searchByName($name) {
        $url = 'https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.$name;
        $response = Http::get($url)->json();
        $drinks = $response['drinks'];
        $drinks = json_decode(json_encode($drinks));

        foreach ($drinks as $drink) {
            $id = $drink->idDrink;

            if (Post::where('drink_id', $id)->exists()) {
                $count = Post::where('drink_id', $id)->count(); //get the topic count per drink
                $positive = Post::where('drink_id', $id)->where('classification', 'Positive')->count(); //get the total positive count
                $neutral =  Post::where('drink_id', $id)->where('classification', 'Neutral')->count(); //get the total neutral count
                $negative = Post::where('drink_id', $id)->where('classification', 'Negative')->count(); //get the total negative count

                $drink->postCount = $count;
                $drink->positive = round(($positive / $count) * 100, 0); 
                $drink->neutral = round(($neutral / $count) * 100, 0); 
                $drink->negative = round(($negative / $count) * 100, 0); 
            } else {
                $drink->postCount = 0;
            }
        }

        // sort the object by number of post
        usort($drinks, fn($a, $b) => $b->postCount - $a->postCount);

        return response()->json(['drinks' => $drinks]);
    }   

}
