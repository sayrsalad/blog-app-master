@extends('layouts.app')

@section('content')
    <style>
        /* Card Hover Animation */
        .card:hover{
            box-shadow: 8px 8px 5px rgb(114, 114, 114);    
            transform: scale(1.1);
        }
        /* ----------- */
    </style>
    <br>
    <h2>{{ $drink['strDrink'] }} Cocktail Profile</h2>
    <br>

    <a class="btn btn-outline-primary mb-3" href="/post/write/{{ $drink['idDrink'] }}" role="button">Write Post</a>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <img class="card-img-top" src="{{ $drink['strDrinkThumb'] }}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">{{ $drink['strDrink'] }}</h5>
                    <p class="card-text"><strong>Instruction: </strong>{{ $drink['strInstructions'] }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Tags: {{ $drink['strTags'] }}</strong></li>
                    <li class="list-group-item"><strong>Category: {{ $drink['strCategory'] }}</strong></li>
                    <li class="list-group-item"><strong>Glass: {{ $drink['strGlass'] }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-8">
            <h3 class="text-center">Related Blog Post</h3>
            <br>

            @forelse ($posts as $post)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{!! \Illuminate\Support\Str::limit($post->desc, 150, $end='.......................') !!}</p>
                        <div class="text-right">
                            <a href="/post/{{$post->id}}" class="btn btn-outline-primary btn-sm active" role="button" aria-pressed="true">See more</a>
                        </div>
                        
                        <footer class="blockquote-footer">Created by: {{ $post->user->name }} {{ $post->user->lastname }}</footer>
                        <footer class="blockquote-footer">Date: {{ $post->created_at }}</footer>
                    </div>

                    <div class="card-footer">
                        <small class="text-muted">Like Count: <strong>{{ $post->like_count }}</strong> || </small>
                        <small class="text-muted">Content Type: <strong>{{ $post->classification }} </strong> || </small>
                        <small class="text-muted">Confidence Percentage: <strong>{{ $post->confidence * 100 }}%</strong> || </small>
                        <br>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary" role="alert">
                    <h4 class="alert-heading">:( No post found!</h4>
                    <p>Be the first one to create post on this topic</p>
                    <hr>
                    <p class="mb-0"></p>
                </div>
             @endforelse
            
             <div class="mt-3">
                {!! $posts->links() !!}
             </div>            
        </div>
    </div>

    <br>
    <h3>{{ $drink['strDrink'] }} Ingredients</h3>
    <br>
    <div class="row">
        @foreach ($ingredients as $ingredient)
            <div class="col">
                <div class="card text-center border-0" style="width: 15rem;">
                    <img class="card-img-top" witdh="100" height="200" src="{{ $ingredient[0] }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ingredient[1] }}</h5>
                        <p class="card-text">{{ $ingredient[2] }}</p>
                    </div>
                </div>
            </div>
        @endforeach        
    </div>

    <br>
    <br>





@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/cocktails/profile.js')}}"></script>
@endsection