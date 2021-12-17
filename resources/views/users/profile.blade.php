@extends('layouts.app')

@section('css')

@endsection

@section('content')

    <style>
        /* Card Hover Animation */
        .card:hover{
            box-shadow: 8px 8px 5px rgb(114, 114, 114);    
            transform: scale(1.1);
        }
        /* ----------- */
    </style>
{{-- Modal for comment, when the user clicked comment button for a specific post, users/profile.js will show this modal --}}
@include('modals.modal-comment')
@include('modals.modal-delete-confirmation')
@include('modals.modal-list-like')
@include('modals.modal-list-comment')

<div class="row m-5">
    <div class="col-sm-4">
        {{-- User Information --}}
        <div class="card mb-2" style="width: 18rem;">
            <div class="text-center">
                <img src="https://scontent.fmnl7-1.fna.fbcdn.net/v/t1.6435-9/69841700_2927629657254330_3139028448018694144_n.jpg?_nc_cat=109&amp;ccb=1-3&amp;_nc_sid=174925&amp;_nc_eui2=AeE9Ypckzov7JnwWDm7-wexCYlit_vP-hZ1iWK3-8_6FnSmKC4Tq40bYzAunEOsydYhnGfwAN47kTgEash53IgRn&amp;_nc_ohc=dxJwOoh0w5AAX-tXuZN&amp;_nc_ht=scontent.fmnl7-1.fna&amp;oh=2abfa03f9ef65e906dd9a8312f181c5d&amp;oe=6088CA85" width="200" height="200" class="rounded-circle" alt="Cinque Terre">
            </div>
            <div class="card-body">
              <p class="card-text">Name: <strong>{{ $user->title." ".$user->name." ".$user->lastname }}</strong></p>
              <p class="card-text">Email: <strong>{{ $user->email }}</strong></p>
              <p class="card-text">Country: <strong>{{ $user->country }}</strong></p>
            </div>
        </div>

        {{-- Posts Statistics Information --}}
        <div class="card mb-2" style="width: 18rem;">
            <div class="card-header">
                Overall Posts Information
            </div>

            <ul class="list-group list-group-flush">
              <li class="list-group-item">No. of post: {{ $user->postCount }}</li>
              <li class="list-group-item">Likes Received: {{ $user->likeReceived }}</li>
              <li class="list-group-item">Comments Received: {{ $user->commentReceived }}</li>
            </ul>
        </div>

        {{-- Favorite drink topic --}}
        <div class="card" style="width: 18rem;">
            <div class="card-header">
                Favourite Topics
            </div>

            <div></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-auto">
                            <img src="https://www.thecocktaildb.com/images/media/drink/0clus51606772388.jpg" width="50" height="50" class="rounded-circle" alt="">
                        </div>
                        <div class="col-8">
                            <span class="d-inline-block">
                                <span class="ml-0"><a href="/user/13">
                                    <strong>Gin Bilog</strong></a>
                                </span>
                                <br>
                                <footer class="blockquote-footer">
                                    3 Topics Created
                                </footer>
                            </span>
                        </div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="row">
                        <div class="col-auto">
                            <img src="https://www.thecocktaildb.com/images/media/drink/0clus51606772388.jpg" width="50" height="50" class="rounded-circle" alt="">
                        </div>
                        <div class="col-8">
                            <span class="d-inline-block">
                                <span class="ml-0"><a href="/user/13">
                                    <strong>Gin Bilog</strong></a>
                                </span>
                                <br>
                                <footer class="blockquote-footer">
                                    3 Topics Created
                                </footer>
                            </span>
                        </div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="row">
                        <div class="col-auto">
                            <img src="https://www.thecocktaildb.com/images/media/drink/0clus51606772388.jpg" width="50" height="50" class="rounded-circle" alt="">
                        </div>
                        <div class="col-8">
                            <span class="d-inline-block">
                                <span class="ml-0"><a href="/user/13">
                                    <strong>Gin Bilog</strong></a>
                                </span>
                                <br>
                                <footer class="blockquote-footer">
                                    3 Topics Created
                                </footer>
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>


    </div>

    {{-- Recent posts --}}
    <div class="col-md-8">
        {{-- Notification alert when the user deletes a post --}}
        <div class="mt-2" id="notificationDeletePostContainer"></div>
        <h5>Recent Posts</h5>

        @foreach ($posts as $post)
            <form>
                <input type="hidden" name="postId" value="{{ $post->id }}">
                <div class="card mt-3">
                    {{-- Notification alert when the user like or commented on the post --}}
                    <div class="mt-2" id="notificationContainer{{ $post->id }}"></div>
                    <div class="card-body">
                        <h5 class="card-title float-left">{{ $post->title }}</h5>
                        {{-- Check if the user who logged in owns the post --}}
                        @if ($post->selfPost)
                            <div class="dropdown float-right dropleft">
                                <button class="btn btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="/post/edit/{{ $post->id }}">
                                        <span class="far fa-edit" aria-hidden="true"></span> 
                                        Edit post
                                    </a>
                                    
                                    <button type="btnDeletePost" class="dropdown-item">
                                        <span class="far fa-trash-alt" aria-hidden="true"></span> 
                                        Remove
                                    </button> 
                                </div>
                            </div>
                        @endif

                        <br><br>
                        <em>Classification: {{ $post->classification }} | Confidence: {{ $post->confidence }}%</em>
                        
                        <p class="card-text">{{ $post->desc }}</p>
                        
                        <footer class="blockquote-footer mb-1">Date: {{ $post->created_at }}</footer>
        
                        <div class="d-flex justify-content-between mb-0">
                            <div>
                                <button type="btnShowLikeList" class="btn btn-link">
                                    <i class="fas fa-thumbs-up" aria-hidden="true"></i>
                                    <span class="ml-1" id="likeCounter{{ $post->id }}">{{ $post->likeCount }} Likes</span>
                                </button></div><div>
                                
                                <button type="btnShowCommentList" class="btn btn-link">
                                    <i class="fas fa-comment" aria-hidden="true"></i>
                                    <span class="ml-1" id="commentCounter{{ $post->id }}">{{ $post->commentCount }} Comments</span>
                                </button>
                            </div>
                        </div>
                    </div>
        
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm text-center">
                                <button type="btnLike" class="btn btn-link" id="btnLike{{ $post->id }}">
                                    @if ($post->isLike) 
                                        <span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>
                                    @else
                                        <span class="far fa-thumbs-up fa-2x" aria-hidden="true"></span>
                                    @endif
                                </button></div><div class="col-sm text-center">
            
                                <button type="btnComment" class="btn btn-link" id="btnComment{{ $post->id }}">
                                    @if ($post->isCommented)
                                        <span class="fas fa-comment fa-2x" aria-hidden="true"></span>
                                    @else
                                        <span class="far fa-comment fa-2x" aria-hidden="true"></span>
                                    @endif
                                </button>
                            </div>
        
                            <div class="col-sm text-center">
                                <a href="/post/{{ $post->id }}">
                                    <i class="far fa-eye fa-2x" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
        
                </div>
            </form>
        @endforeach
        {!! $posts->links() !!}
    </div>
</div>

{{-- Recent Comments --}}
<h5>Recent Comments</h5>
<div class="mt-2" id="notificationUpdateCommentContainer"></div>
<div class="mt-2" id="notificationDeleteCommentContainer"></div>
<div class="row">
    @foreach ($comments as $comment)
    <form class="col-sm-4">        
        <input type="hidden" name="commentId" value="{{ $comment->id }}">
        <div class="card border-dark border-0 comments" style="" data-string="Sadye Beahan Voluptas veritatis saepe voluptates et est architecto in. Neutral">
            <div class="card-body text-dark">
                {{-- Check if the user who logged in owns the comment --}}
                @if ($comment->selfComment)
                    <div class="dropdown float-right dropleft">
                        <button class="btn btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button type="btnEditComment" class="dropdown-item">
                                <span class="far fa-edit" aria-hidden="true"></span>  
                                Edit comment
                            </button> 

                            <button type="btnDeleteComment" class="dropdown-item">
                                <span class="far fa-trash-alt" aria-hidden="true"></span> 
                                Remove
                            </button> 
                        </div>
                    </div>
                @endif

                <span class="d-inline-block">
                    <img src="https://scontent.fmnl7-1.fna.fbcdn.net/v/t1.6435-9/69841700_2927629657254330_3139028448018694144_n.jpg?_nc_cat=109&amp;ccb=1-3&amp;_nc_sid=174925&amp;_nc_eui2=AeE9Ypckzov7JnwWDm7-wexCYlit_vP-hZ1iWK3-8_6FnSmKC4Tq40bYzAunEOsydYhnGfwAN47kTgEash53IgRn&amp;_nc_ohc=dxJwOoh0w5AAX-tXuZN&amp;_nc_ht=scontent.fmnl7-1.fna&amp;oh=2abfa03f9ef65e906dd9a8312f181c5d&amp;oe=6088CA85" width="50" height="50" class="rounded-circle" alt="">
                </span>

                <span class="d-inline-block">
                    <span class="ml-2"><strong>{{ $comment->user->title."".$comment->user->name." ".$comment->user->lastname }}</strong></span><br>
                </span>
                <br>

                <small><a href="/post/{{ $comment->post_id }}">Commented on post #{{ $comment->post_id }}</a></small>
                <h6 class="card-subtitle mt-0 text-muted">{{ $comment->comment }}</h6>
                <p class="card-text"><small>Type: {{ $comment->classification }} -- Confidence: {{ $comment->confidence }}% </small></p>
                <footer class="blockquote-footer">Date: {{ $comment->created_at }}</footer>
            </div>
        </div>
    </form>
   
    @endforeach
 
    {!! $comments->links() !!}
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/users/profile.js')}}"></script>
@endsection
