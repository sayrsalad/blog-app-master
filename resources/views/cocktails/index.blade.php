@extends('layouts.app')

@section('content')
    <style>
        /* Card Hover Animation */
        .card:hover{
            box-shadow: 8px 8px 5px rgb(114, 114, 114);    
            transform: scale(1.1);
        }
        /* ----------- */

        /* Loading */
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(https://i.pinimg.com/originals/78/e8/26/78e826ca1b9351214dfdd5e47f7e2024.gif) center no-repeat #fff;
        }
        /* ----------- */

    </style>

    {{-- Loading  --}}
    <div class="se-pre-con"></div>

    <h2 class="mt-3 mb-3">Cocktails</h2>

    <div class="row">
        <div class="col-sm-10">
            <div class="input-group">
                {{-- Search bar directly sa controller  --}}
                <input type="text" class="form-control" id="searchBar" placeholder="Search id directly from the api">
                <div class="input-group-append">
                <button class="btn btn-secondary" id="btnSearchFromApi" type="button">
                    <i class="fa fa-search"></i>
                </button>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <button type="button" id="btnRandomCocktail" class="btn btn-secondary btn-block">Random Cocktail</button>
        </div>
    </div>

    {{-- This will be used to show if the search result is null DITO LALABAS YUNG NOTIFICATION KAPAG WALANG NAHANAP NA ID --}}
    <div class="mt-3" id=notificationContainer></div>

    <hr>

    {{-- For random search (Single Cocktail) DITO LALABAS YUNG MGA SINGLE DRINK RESULT KAPAG PININDOT YUNG RANDOM COCKTAIL OR SEARCH BY ID --}}
    <div id="singleDrinkContainer"></div>
        
    {{-- For Drink Search (List of Cocktails) DITO LALABAS YUNG LIST OF DRINKS KAPAG NI SEARCH BY NAME OR FIRST LETTER --}}
    <div id="drinksContainer"></div>


    <h5 class="mt-3">Trending Topics</h5>
    {{-- For Drinks with existing blog post --}}


    <div class="input-group mb-3">
        {{-- Search bar For Drinks dun sa mismong cards container (TopicsContainer) --}}
        <input type="search" class="form-control rounded filter" placeholder="Search by id, name, category, and glass" aria-label="Search" aria-describedby="search-addon" />
    </div>

    <div id="topicsContainer"></div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/cocktails/index.js')}}"></script>
@endsection