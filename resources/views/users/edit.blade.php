@extends('layouts.app')

@section('content')
<style>
    /* Card Hover Animation */
    .card:hover {
        box-shadow: 8px 8px 5px rgb(114, 114, 114);
        transform: scale(1.1);
    }

    /* ----------- */

    /* Loading */
    .no-js #loader {
        display: none;
    }

    .js #loader {
        display: block;
        position: absolute;
        left: 100px;
        top: 0;
    }

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
<h2>Edit your profile</h2>
<form action="{{ route('user.update')}}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $user->id }}">
    <div class="form-row">
        <div class="form-group col-md-2">
            <input type="text" class="form-control" name="title" id="title" placeholder="Title (Ex. Mr, Mrs)" value="{{ $user->title }}">
        </div>
        <div class="form-group col-md-5">
            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First Name" value="{{ $user->name }}">
        </div>

        <div class="form-group col-md-5">
            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last Name" value="{{ $user->lastname }}">
        </div>

        <div class="form-group col-md-6">
            <label for="inputEmail">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $user->email }}">
        </div>

        <div class="form-group col-md-6">
            <label>Province</label>
            <input type="text" id="location" class="form-control" name="location">
            <label id="lblresult"></label>

        </div>


        <!--      <div class="form-group col-md-11 m-1 custom-file">
                <input type="file" class="custom-file-input" id="photo">
                <label class="custom-file-label" for="validatedCustomFile">Choose image</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
            </div> -->

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>
</form>
<br>
<form action="{{ route('user.password')}}">
    <input type="submit" value="Change Password" class="btn btn-secondary"/>
</form>
<br>
<br>
<br>
<br>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfmNYNS8DPcugR-gQWFqNobagCwetRn70&libraries=places"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() {
        google.maps.event.addDomListener(window, 'load', initilize);

        function initilize() {
            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                console.log(place);
                var address = place.address_components;
                var city, state, country;
                address.forEach(function(component) {
                    var types = component.types;
                    if (types.indexOf('locality') > -1) {
                        city = component.long_name;
                    }

                    if (types.indexOf('administrative_area_level_2') > -1) {
                        state = component.short_name;
                    }

                    if (types.indexOf('country') > -1) {
                        country = component.long_name;
                    }

                });

                var location = "<input type='hidden' id='city' name='city' value='" + city + "'>";
                location += "<input type='hidden' id='state' name='state' value='" + state + "'>";
                location += "<input type='hidden' id='country' name='country' value='" + country + "'>";
                location += "<input type='hidden' id='lat' name='lat' value='" + place.geometry.location.lat() + "'>";
                location += "<input type='hidden' id='lng' name='lng' value='" + place.geometry.location.lng() + "'>";
                document.getElementById('lblresult').innerHTML = location
            });

        };
        $(document).on("click", ".update", function() {
            var edit_id = $(this).data('id');
            var title = $('#title').val();
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var email = $('#email').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var country = $('#country').val();
            var lat = $('#lat').val();
            var lng = $('#lng').val();

            if (firstName != '' && email != '') {
                $.ajax({
                    url: 'update',
                    type: 'post',
                    data: {
                        _token: CSRF_TOKEN,
                        editid: edit_id,
                        firstName: firstName,
                        email: email,
                        title: title,
                        lastName: lastName,
                        city: city,
                        state: state,
                        country: country,
                        lat: lat,
                        lng: lng,
                    },
                    success: function(response) {
                        alert(response);
                    }
                });
            } else {
                alert('Fill all fields');
            }
        });
    });
</script>





@endsection