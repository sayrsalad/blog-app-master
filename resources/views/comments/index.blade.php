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

<div class="container">
    <br>
    <h2>Blog Posts</h2>
    <br>
    <div class="row">
        <div class="col-9">
            @foreach ($posts as $post)
                <div class="card border-dark mb-3" style="">
                    <div class="card-header">Blog post #{{ $post->id }}</div>
                    <div class="card-body text-dark">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-justify">{!! \Illuminate\Support\Str::limit($post->desc, 150, $end='.......................') !!}</p>
                    <footer class="blockquote-footer">Created by: {{ $post->user->name }} {{ $post->user->lastname }}</footer>
                    <footer class="blockquote-footer">Date: {{ $post->created_at }}</footer>
                    </div>
                    
                    <div class="card-footer">
                        <small class="text-muted">Like Count: <strong>{{ $post->like_count }}</strong> || </small>
                        <small class="text-muted">Content Type: <strong>{{ $post->classification }} </strong> || </small>
                        <small class="text-muted">Confidence Percentage: <strong>{{ $post->confidence * 100 }}%</strong> || </small>
                        <div class="float-right"><a href="/post/{{$post->id}}"><i class="far fa-eye fa-3x" aria-hidden="true"></i></a></div>
                        <br>
                    </div>
                </div>
            @endforeach
            {!! $posts->links() !!}
        </div>
        <div class="col-3">
            {{-- Recent Comments --}}
            <div class="card border-dark pl-1" style="">
                <h5 class="ml-3 mt-3">Recent Comments</h5>
                @foreach ($comments as $comment)
                    <div class="card-body text-dark">                      
                        <span class="d-inline-block">
                            <img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="50" height="50" class="rounded-circle" alt="">
                        </span>
                        <span class="d-inline-block">
                            <span class="ml-2"><strong>{{ $comment->user->title. " ".$comment->user->name. " ".$comment->user->lastname. " "}}</strong></span><br>
                        </span>
                        <br>
                        <small><a href="/post/{{$comment->post_id}}">Commented on post #{{ $comment->post_id }}</a></small>
                        <h6 class="card-subtitle mt-1 text-muted">{{ $comment->comment }}</h6>
                        <p class="card-text"><small>Type: {{ $comment->classification }} -- Confidence: {{ $comment->confidence }}% </small></p>
                        <footer class="blockquote-footer">{{ $comment->created_at }}</footer>
                    </div>
                @endforeach                
            </div>
        </div>
    </div>
      
    </div>
</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/comments/index.js')}}"></script>
@endsection