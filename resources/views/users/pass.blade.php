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
                

    <br>
    <h2>Change Password</h2>
    <form action="{{route('user.cpassword')}}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="form-row">
  
            <div class="form-group col-md-6">
                <label for="password">New Password</label>
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="form-group col-md-6">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>
        </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </div>
    </form>
    <br>

    <br>
    <br>
    <br>
    <br>

    




@endsection