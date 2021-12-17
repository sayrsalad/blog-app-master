<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CocktailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;

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


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');


// secured all routes, to make sure the user login/register first before accessing the web application
Route::group(['middleware' => 'auth'], function() {
    // Cocktail Controller Routes
    Route::get('/', [CocktailController::class, 'index'])->middleware(['auth'])->name('dashboard'); // return view page
    Route::get('/cocktail/{id}', [CocktailController::class, 'cocktailProfile']); // return view page with data
    Route::get('/getTopics', [CocktailController::class, 'getTopics']); // return json of topics
    Route::get('/getRandomCocktail', [CocktailController::class, 'getRandom']); // return json of single cocktail
    Route::get('/searchByDrinkID/{id}', [CocktailController::class, 'searchByID']); // return json of single cocktail
    Route::get('/searchByFirstLetter/{letter}', [CocktailController::class, 'searchByFirstLetter']); // return json of list of drinks that starts with letter A-Z
    Route::get('/searchByDrinkName/{name}', [CocktailController::class, 'searchByName']); // return json list of drinks that has a name similar to {{name}}

    // Comment Controller Routes
    Route::get('/comments', [CommentController::class, 'comments']); // return view page with data
    Route::post('/comment/store', [CommentController::class, 'store']); // return json with comment data
    Route::get('/comment/edit/{id}', [CommentController::class, 'edit']); // return json response
    Route::post('/comment/update', [CommentController::class, 'update']); // return json response
    Route::post('/comment/destroy', [CommentController::class, 'destroy']); // return json response


    // User Controller
    Route::get('/user/{id}', [UserController::class, 'userProfile']); // return view page
    Route::get('/user/', [UserController::class, 'analysis']);
    // User Edit Info
    Route::get('/user/profile/edit', [UserController::class, 'edit']);// return view page
    
    // User Update Info
    Route::post('/user/profile/update', ['as' => 'user.update', 'uses' => 'App\Http\Controllers\UserController@update']);
    Route::get('/user/profile/pass', ['as' => 'user.password', 'uses' => 'App\Http\Controllers\UserController@Pass']);
    Route::post('/user/profile/changepass', ['as' => 'user.cpassword', 'uses' => 'App\Http\Controllers\UserController@changePass']);
     //return json
    // User Delete Account
    Route::post('/user/destroy', [UserController::class, 'destroy']); 

    // Like Controller
    Route::get('/postLikes/{id}', [LikeController::class, 'postLikes']); // return json response
    Route::post('/likePost', [LikeController::class, 'likePost']); // return json response

    // Post Controller Routes
    Route::get('/posts', [PostController::class, 'index']); // return view page
    Route::get('/getPosts', [PostController::class, 'getPosts']); // return json response
    Route::get('/postComments/{id}', [CommentController::class, 'postComments']);
    Route::get('/post/{id}', [PostController::class, 'postProfile']); // return view page
    Route::post('/post/destroy', [PostController::class, 'destroy']); // return view page
    Route::post('/post/store', [PostController::class, 'store']); // return view page

    //1 - Choose Topic
    Route::get('/choose-topic', [CocktailController::class, 'chooseTopic']); // return view page 


    //3 - Write Posts 
    Route::get('/post/write/{id}', [PostController::class, 'write']);

    // Edit Posts
    Route::get('/post/edit/{id}', [PostController::class, 'edit']);
    // Update Posts
    Route::post('/post/update', [PostController::class, 'update']);

    //Locate
    Route::get('/locate', [LocationController::class, 'index']);


});


require __DIR__.'/auth.php';
