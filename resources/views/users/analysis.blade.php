@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f9f9fa
    }

    .padding {
        padding-top: 3rem !important
    }

    .user-card-full {
        overflow: hidden
    }

    .card {
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
        box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
        border: none;
        margin-bottom: 30px;
    }

    .m-r-0 {
        margin-right: 0px
    }

    .m-l-0 {
        margin-left: 0px
    }

    .user-card-full .user-profile {
        border-radius: 5px 0 0 5px
    }

    .bg-c-lite-green {
        background: -webkit-gradient(linear, left top, right top, from(#f29263), to(#ee5a6f));
        background: linear-gradient(to right, #ee5a6f, #f29263)
    }

    .user-profile {
        padding: 20px 0;
        border-radius: 5px 0px 0px 5px;
    }

    .card-block {
        padding: 1.25rem
    }

    .m-b-25 {
        margin-bottom: 25px
    }

    .img-radius {
        border-radius: 5px
    }

    h6 {
        font-size: 14px
    }

    .card .card-block p {
        line-height: 25px
    }

    @media only screen and (min-width: 1400px) {
        p {
            font-size: 14px
        }
    }

    .card-block {
        padding: 1.25rem
    }

    .b-b-default {
        border-bottom: 1px solid #e0e0e0
    }

    .m-b-20 {
        margin-bottom: 20px
    }

    .p-b-5 {
        padding-bottom: 5px !important
    }

    .card .card-block p {
        line-height: 25px
    }

    .m-b-10 {
        margin-bottom: 10px
    }

    .text-muted {
        color: #919aa3 !important
    }

    .b-b-default {
        border-bottom: 1px solid #e0e0e0
    }

    .f-w-600 {
        font-weight: 600
    }

    .m-b-20 {
        margin-bottom: 20px
    }

    .m-t-40 {
        margin-top: 20px
    }

    .p-b-5 {
        padding-bottom: 5px !important
    }

    .m-b-10 {
        margin-bottom: 10px
    }

    .m-t-40 {
        margin-top: 20px
    }

    .user-card-full .social-link li {
        display: inline-block
    }

    .user-card-full .social-link li a {
        font-size: 20px;
        margin: 0 10px 0 0;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out
    }

    canvas {
        width: 100% !important;
        height: 200px;
    }

</style>
<div class="padding">
    <div class="card">
        <div class="bg-c-lite-green user-profile">
            <div class="card-block text-center text-white">
                <div class="m-b-25"> <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius" alt="User-Profile-Image"> </div>
                <h6 class="f-w-600">{{ $user->title }} {{ $user->name }} {{ $user->lastname }}</h6>
                <!-- <p>Web Designer</p> <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i> -->
            </div>
        </div>
    </div>
    <div class="card">
        <div class="row">
            <div class="col">
                <div class="card-block">
                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="m-b-10 f-w-600">Email</p>
                            <h6 class="text-muted f-w-400">{{ $user->email }}</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="m-b-10 f-w-600">Country</p>
                            <h6 class="text-muted f-w-400">{{ $user->country }}</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mt-4 m-b-10 f-w-600">City</p>
                            <h6 class="text-muted f-w-400">{{ $user->city }}</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mt-4 m-b-10 f-w-600">Street</p>
                            <h6 class="text-muted f-w-400">{{ $user->street_name }}</h6>
                        </div>
                    </div>
                    <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Post & Comment Analytics</h6>
                    <div class="row">
                        <!-- <div class="col-sm-6">
                            <p class="m-b-10 f-w-600">Recent</p>
                            <h6 class="text-muted f-w-400">Sam Disuja</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="m-b-10 f-w-600">Most Viewed</p>
                            <h6 class="text-muted f-w-400">Dinoter husainm</h6>
                        </div> -->
                        
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold">Overall</h6>
                                </div>
                                <!-- Card Body -->

                                <div class="m-auto pt-3">Total Posts & comments: {{$user->totalpc}}</div> 
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="overall" class="m"></canvas>
                                    </div>
                                    <hr>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Positive
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> Negative
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Neutral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold">Posts</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="m-auto pt-3">Total Posts & comments: {{$user->postCount}}</div> 
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="posts"></canvas>
                                    </div>
                                    <hr>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Positive
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> Negative
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Neutral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold">Comments</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="m-auto pt-3">Total Posts & comments: {{$user->commentCount}}</div> 
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="comments"></canvas>
                                    </div>
                                    <hr>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Positive
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> Negative
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Neutral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <ul class="social-link list-unstyled m-t-40 m-b-10">
                        <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="facebook" data-abc="true"><i class="mdi mdi-facebook feather icon-facebook facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="twitter" data-abc="true"><i class="mdi mdi-twitter feather icon-twitter twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="instagram" data-abc="true"><i class="mdi mdi-instagram feather icon-instagram instagram" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
    var user = {!! json_encode($user->toArray()) !!};
</script>

<script src="{{ asset('js/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('js/charts/pieChart.js') }}"></script>
@endsection