@extends('layouts.app')

@section('content')

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
                            <img src="https://scontent.fmnl7-1.fna.fbcdn.net/v/t1.6435-9/69841700_2927629657254330_3139028448018694144_n.jpg?_nc_cat=109&amp;ccb=1-3&amp;_nc_sid=174925&amp;_nc_eui2=AeE9Ypckzov7JnwWDm7-wexCYlit_vP-hZ1iWK3-8_6FnSmKC4Tq40bYzAunEOsydYhnGfwAN47kTgEash53IgRn&amp;_nc_ohc=dxJwOoh0w5AAX-tXuZN&amp;_nc_ht=scontent.fmnl7-1.fna&amp;oh=2abfa03f9ef65e906dd9a8312f181c5d&amp;oe=6088CA85" width="50" height="50" class="rounded-circle" alt="">
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
<script type="text/javascript" src="{{asset('js/pages/comment.js')}}"></script>
@endsection