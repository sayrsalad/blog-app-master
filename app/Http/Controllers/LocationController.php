<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Auth;

class LocationController extends Controller
{
    public function index(){
    	$user = User::where('id', Auth::id())->first();
        return view('pages.location', ['user' => $user] );
    }


}
