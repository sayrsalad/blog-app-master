@extends('layouts.app')

@section('content')

<style>
    .card:hover{
        box-shadow: 8px 8px 5px rgb(114, 114, 114);    
        transform: scale(1.1);
    }
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
</style>

<div class="container">
    
    {{-- <div class="se-pre-con"></div> Loading --}}
    <h3 class="mt-2">Choose topic</h3>

    <div class="row">
        <div class="col-sm-10">
            <div class="input-group">
                {{-- Search bar  --}}
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
    <hr>

    {{-- For random search --}}
    <div id="singleDrinkContainer"></div>
    
    {{-- For Drink Search  --}}
    <div id="drinksContainer"></div>

    {{-- This will be used to show if the search result is null --}}
    <div class="mt-3" id=notificationContainer></div>
    
    <h5 class="mt-3">Trending Topics</h5>
    {{-- For Drinks with existing blog post --}}

    <div class="input-group mb-3">
        {{-- Search bar For Drinks with existing blog post --}}
        <input type="search" class="form-control rounded filter" placeholder="Search by id, name, category, and glass" aria-label="Search" aria-describedby="search-addon" />
    </div>

    <div id="topicsContainer"></div>
    <div class="row">
        {{-- <div id="topicsContainer"></div> --}}
        {{-- <div class="col-sm">
            <div class="card mt-3" style="width: 20rem;" data-string="Alexander 11014 Ordinary Drink Cocktail glass">
                <img class="card-img-top" src="https://www.thecocktaildb.com/images/media/drink/0clus51606772388.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Alexander (11014)</h5>
                    <p class="card-text">Category: Ordinary Drink</p>
                    <p class="card-text">Glass: Cocktail glass</p>
                    <p class="card-text">
                        <i class="fas fa-user-edit" aria-hidden="true"></i> 8 blog post created
                    </p>

                <div class="progress" style="height: 40px;">
                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" data-toggle="tooltip" data-placement="top" title="25% Positive Post" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <i class="fas fa-smile-beam fa-2x" aria-hidden="true"></i>
                    </div>
                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" data-toggle="tooltip" data-placement="top" title="25% Neutral Post" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <i class="fas fa-meh fa-2x" aria-hidden="true"></i>
                    </div>
                    <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" data-toggle="tooltip" data-placement="top" title="50% Negative Post" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <i class="fas fa-angry fa-2x" aria-hidden="true"></i>
                    </div>
                </div>

                <a href="/cocktail/11014" class="btn btn-primary mt-4 float-right">Pick</a></div></div>
        </div>
        <div class="col-sm">col-sm</div>
        <div class="col-sm">col-sm</div> --}}
    </div>

    <br>
    <br>
</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/posts/choose-topic.js')}}"></script>
@endsection