<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Alcohol Blog Post</title>
        <link rel="icon" href="https://www.pngkey.com/png/full/510-5109524_open-emoji-cerveja.png">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
       
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
        <script src="https://kit.fontawesome.com/3cc49842c0.js" crossorigin="anonymous"></script>
        
        {{-- <link href="{{ asset('css/main.css') }}" rel="stylesheet"> --}}
        {{-- CSS FOR A SPECIFIC PAGE --}}
        @yield('css') 
        
    </head>
    <body>
        <div id="app">
            @include('inc.navbar')
            <div class="container">
                @yield('content')
            </div>
        </div>
        @yield('content2')
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

        {{-- For Validation --}}
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
        
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
                
        @yield('js')
    </body>
</html>
