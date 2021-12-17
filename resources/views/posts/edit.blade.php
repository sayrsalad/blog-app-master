@extends('layouts.app')

@section('content')

<div class="container">
    
    {{-- <div class="se-pre-con"></div> Loading --}}
    <h3 class="mt-2">Edit your content</h3>
    
    <div id="notificationContainer"></div>
    
    <div class="row">
        {{-- Topic Info --}}
        <div class="col-12 col-md-3">
            <div class="card">
                <img class="card-img-top" src="{{ $drink['strDrinkThumb']}}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Topic: {{ $drink['strDrink']}}</h5>
                    <p class="card-text"><strong>Instruction: </strong>{{ $drink['strInstructions']}}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Tags: {{ $drink['strTags']}}</strong></li>
                    <li class="list-group-item"><strong>Category: {{ $drink['strCategory']}}</strong></li>
                    <li class="list-group-item"><strong>Glass: {{ $drink['strGlass']}}</strong></li>
                </ul>
            </div>
        </div>

        {{-- Write Post Form --}}
        <div class="col-12 col-md-6">
            <form id="postForm">
                <input type="hidden" id="drinkId" value="{{ $drink['idDrink']}}">  
                <input type="hidden" id="postId" value="{{ $post->id }}">  
                <div class="form-group">
                  <label for="title">Post Title</label>
                  <input type="text" class="form-control" id="title" name="title" placeholder="Input your title here" value="{{ $post->title }}">
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="18">{{ $post->desc }}</textarea>
                </div>

                <button class="btn btn-primary float-right" type="submit">Submit post</button>
            </form>
        </div>

        {{-- Location Info --}}
        <div class="col-12 col-md-3">
            Dito mo lagay yung information ng location pre kung saang bar ganun pre
        </div>
    </div>

</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/posts/edit.js')}}"></script>
@endsection