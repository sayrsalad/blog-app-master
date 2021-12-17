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

    {{-- Modal for comment, when the user clicked comment button for a specific post
    post.js will show this modal --}}
    @include('modals.modal-comment')
    @include('modals.modal-delete-confirmation')
    @include('modals.modal-list-like')

    {{-- Loading  --}}
    <div class="se-pre-con"></div>
    
    <br>
    <h2>Blog Post</h2>
    {{-- Notification alert when the user deletes a post --}}
    <div class="mt-2" id="notificationDeletePostContainer"></div>
    <br>
    <div class="row">
        <div class="col-sm-8">
            {{-- HIDDEN FIELD FOR POST ID TO BE USED IN CREATING/UPDATING/DELETING OF COMMENTS AND POSTS --}}
            <input type="hidden" id="postId" name="postId" value="{{ $post->id }}">

            <div class="card border-dark mb-3" style="">
                <div class="card-header">Blog post #{{ $post->id }}
                    {{-- Check if the user owns the post --}}
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
                                <form>
                                    <button type="btnDeletePost" class="dropdown-item">
                                    <span class="far fa-trash-alt" aria-hidden="true"></span> 
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    
                </div>
                
                <div class="card-body text-dark">
                    <div id="likeNotificationContainer"></div>
                  <h5 class="card-title">{{ $post->title }}</h5>
                  <p class="card-text text-justify">{{ $post->desc }}</p>
                  <footer class="blockquote-footer">Date: {{ $post->created_at }}</footer>
                </div>
                <div class="card-footer">
                    <button type="btnLike"  class="btn btn-link" id="btnShowLikeList">
                        <small class="text-muted" id="likeCount">Like Count: <strong>{{ $post->like_count }}</strong> || </small>
                    </button>
                    <small class="text-muted">Content Type: <strong>{{ $post->classification }} </strong> || </small>
                    <small class="text-muted">Confidence Percentage: <strong>{{ $post->confidence * 100 }}%</strong> || </small>
                    <form id="likeForm">
                        <input type="hidden" id="post_id" value="{{ $post->id }}"/>
                        <button type="submit"  class="btn btn-link float-right" id="btnLike">
                        @if ($post->isLike)
                            <span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>
                        @else
                            <span class="far fa-thumbs-up fa-2x" aria-hidden="true"></span>
                        @endif
                        </button>
                    </form>
                    <br>
                </div>
            </div>

            {{-- Comments --}}
            <h6>Comments</h6>
            <div class="progress" style="height: 45px;">
                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: {{ $percentage[0] }}%" aria-valuenow="{{ $percentage[0] }}" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-smile-beam fa-2x">{{ "  ".$percentage[0]."%" }}</i> </div>
                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: {{ $percentage[1] }}%" aria-valuenow="{{ $percentage[1] }}" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-meh fa-2x">{{ "  ".$percentage[1]."%" }}</i> </div>
                <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" style="width: {{ $percentage[2] }}%" aria-valuenow="{{ $percentage[2] }}" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-angry fa-2x">{{ "  ".$percentage[2]."%" }}</i> </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-sm-8">
                    <input type="search" class="form-control rounded filter" placeholder="Search by name and comment" aria-label="Search" aria-describedby="search-addon" />
                </div>

                <div class="col-4">
                    <select class="form-control" id="selectClassification">
                        <option value="All">All</option>
                        <option value="Positive">Positive</option>
                        <option value="Neutral">Neutral</option>
                        <option value="Negative">Negative</option>
                    </select>
                </div>
            </div>

            <!-- Comment update container -->
            <div id="notificationUpdateDeleteCommentContainer"></div>
            {{-- Comments List --}}
            <div class="row">
                @foreach ($comments as $comment)
                    <form class="col-sm-6">        
                        <input type="hidden" name="commentId" value="{{ $comment->id }}">
                        <div class="card border-dark border-0 comments" style="" data-string="{{ $comment->user->name. " ".$comment->user->lastname }} {{ $comment->comment }} {{ $comment->classification }}">
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
                                    <img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="50" height="50" class="rounded-circle" alt="">
                                </span>
                                <span class="d-inline-block">
                                    <span class="ml-2"><a href="/user/{{ $comment->user_id }}"><strong>{{ $comment->user->title. " ".$comment->user->name. " ".$comment->user->lastname. " "}}</strong></a></span><br>
                                </span>
                                
                                <h6 class="card-subtitle mt-4 text-muted">{{ $comment->comment }}</h6>
                                <p class="card-text"><small>Type: {{ $comment->classification }} -- Confidence: {{ $comment->confidence * 100 }}% </small></p>
                                <footer class="blockquote-footer">Date: {{ $comment->created_at }}</footer>
                            </div>
                        </div>
                    </form>
                @endforeach

                {!! $comments->links() !!}
            </div>

            {{-- Write Comments --}}
            <!--Reply-->
            <div class="card mb-3 wow fadeIn">
                <div class="card-header font-weight-bold">Leave a reply</div>
                <div class="card-body">
                    <!-- Default form reply -->
                    <p>Logged in as <a href="">{{ Auth::user()->name }}</a>,------------
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log out') }}
                            </x-dropdown-link>
                        </form>
                    </p>
                    <form method="POST" id="commentWriteForm" novalidate>
                        <!-- Comment create container -->
                        <div id="notificationContainer"></div>
                        <div class="form-group">
                            <label for="replyFormComment">Your comment</label>
                            <textarea class="form-control" id="replyFormComment" name="replyFormComment" rows="5"></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-info btn-md" id="submit" type="submit">Comment</button>
                        </div>
                    </form>
                    <!-- Default form reply -->
                </div>
            </div>
            <!--/.Reply-->
        </div>

        <div class="col-sm-4">

            <div class="card border-dark mb-5">
                <div class="text-center">
                    <h4>Author Info</h4>
                    <img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="200" height="200" class="rounded-circle" alt="Cinque Terre">
                </div>
                <div class="card-body">
                    <p class="card-text">Name: <a href="/user/{{ $post->user_id }}"><strong>{{ $post->user->name." ".$post->user->lastname }}</strong></a></p>
                    <p class="card-text">Email: <strong>{{ $post->user->email }}</strong></p>
                    <p class="card-text">Country: <strong>{{ $post->user->country }}</strong></p>
                </div>
            </div>
            
            <div class="card border-dark">
                <img class="card-img-top" src="{{ $response['drinks'][0]['strDrinkThumb'] }}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Topic: <a href="/cocktail/{{ $response['drinks'][0]['idDrink'] }}">{{ $response['drinks'][0]['strDrink'] }}</a></h5>
                    <p class="card-text"><strong>Instruction: </strong>{{ $response['drinks'][0]['strInstructions'] }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Tags: {{ $response['drinks'][0]['strTags'] }}</strong></li>
                    <li class="list-group-item"><strong>Category: {{ $response['drinks'][0]['strCategory'] }}</strong></li>
                    <li class="list-group-item"><strong>Glass: {{ $response['drinks'][0]['strGlass'] }}</strong></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Ingredients List --}}
    <h3>{{ $response['drinks'][0]['strDrink'] }} Ingredients</h3>
    <br>
    <div class="row">
        @foreach ($ingredients as $ingredient)
            <div class="col-4">
                <div class="card text-center border-0">
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
    <br>
    <br>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/posts/profile.js')}}"></script>
@endsection