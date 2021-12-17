@extends('layouts.app')

@section('content')
<style>
    .card:hover{
        box-shadow: 8px 8px 5px rgb(114, 114, 114);    
        transform: scale(1.1);
    }
</style>
<div class="container">
    <br>
    <h2>Cocktails with Blogpost</h2>
    <br>
    <div class="input-group">
        <input type="search" class="form-control rounded filter" placeholder="Search by id, name, category, and glass" aria-label="Search" aria-describedby="search-addon" />
    </div>
    <div id="card_list">
      
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/pages/index.js')}}"></script>
@endsection