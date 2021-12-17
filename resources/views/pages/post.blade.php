@extends('layouts.app')

@section('content')

<style>
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

{{-- Modal for comment, when the user clicked comment button for a specific post
    post.js will show this modal --}}
@include('modals.modal-comment')
@include('modals.modal-delete-confirmation')
@include('modals.modal-list-like')
@include('modals.modal-list-comment')


<h2 class="mt-3 mb-3">Blog Post Wall</h2>

<div class="se-pre-con"></div>

<div class="input-group mb-3">
    <input type="search" class="form-control rounded filter" placeholder="Search by id, name, category, title, description and glass" aria-label="Search" aria-describedby="search-addon" />
</div>

<div class="mt-2" id="notificationContainer"></div>

<div id="card_list"></div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/pages/post.js')}}"></script>
@endsection