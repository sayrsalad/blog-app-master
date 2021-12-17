@extends('layouts.app')

@section('content')

<div class="container">
    
    {{-- <div class="se-pre-con"></div> Loading --}}
    <h3 class="mt-2">Write your content</h3>
    
    <div id="notificationContainer"></div>
    
    <div class="row">
        {{-- Topic Info --}}
        <div class="col-12 col-md-3">
            <div class="card">
                <img class="card-img-top" src="{{ $drink[0]['strDrinkThumb']}}" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Topic: {{ $drink[0]['strDrink']}}</h5>
                    <p class="card-text"><strong>Instruction: </strong>{{ $drink[0]['strInstructions']}}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Tags: {{ $drink[0]['strTags']}}</strong></li>
                    <li class="list-group-item"><strong>Category: {{ $drink[0]['strCategory']}}</strong></li>
                    <li class="list-group-item"><strong>Glass: {{ $drink[0]['strGlass']}}</strong></li>
                </ul>
            </div>
        </div>

        {{-- Write Post Form --}}
        <div class="col-12 col-md-6">
            <form id="postForm">
                <input type="hidden" id="drinkId" value="{{ $drink[0]['idDrink']}}">  
                <div class="form-group">
                  <label for="title">Post Title</label>
                  <input type="text" class="form-control" id="title" name="title" placeholder="Input your title here">
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="18"></textarea>
                </div>
                <button class="btn btn-primary float-right" type="submit">Submit post</button>
            </form>
        </div>

        {{-- Location Info --}}
        <div id="map"></div>
        <div class="col-12 col-md-3">
            <div id="panel"></div>
        </div>

 <script>

    let pos;
    let map;
    let bounds;
    let infoWindow;
    let currentInfoWindow;
    let service;
    let infoPane;
    function initMap() {
      // Initialize variables
      bounds = new google.maps.LatLngBounds();
      infoWindow = new google.maps.InfoWindow;
      currentInfoWindow = infoWindow;
      /* TODO: Step 4A3: Add a generic sidebar */
      infoPane = document.getElementById('panel');

      // Try HTML5 geolocation
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
          latit = {{ $user->latitude }};
          longit = {{ $user->longitude }};

          pos = {
            lat: latit,
            lng: longit
          };
          map = new google.maps.Map(document.getElementById('map'), {
            center: pos,
            zoom: 15
          });
          bounds.extend(pos);

          infoWindow.setPosition(pos);
          infoWindow.setContent('Location found.');
          infoWindow.open(map);
          map.setCenter(pos);

          // Call Places Nearby Search on user's location
          getNearbyPlaces(pos);
        }, () => {
          // Browser supports geolocation, but user has denied permission
          handleLocationError(true, infoWindow);
        });
      } else {
        // Browser doesn't support geolocation
        handleLocationError(false, infoWindow);
      }
    }

    // Handle a geolocation error
    function handleLocationError(browserHasGeolocation, infoWindow) {
      // Set default location to Sydney, Australia

      pos = { lat: -33.856, lng: 151.215 };
      map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 15
      });

      // Display an InfoWindow at the map center
      infoWindow.setPosition(pos);
      infoWindow.setContent(browserHasGeolocation ?
        'Geolocation permissions denied. Using default location.' :
        'Error: Your browser doesn\'t support geolocation.');
      infoWindow.open(map);
      currentInfoWindow = infoWindow;

      // Call Places Nearby Search on the default location
      getNearbyPlaces(pos);
    }

    // Perform a Places Nearby Search Request
    function getNearbyPlaces(position) {
      let request = {
        location: position,
        rankBy: google.maps.places.RankBy.DISTANCE,
        keyword: 'bar'
      };
      service = new google.maps.places.PlacesService(map);
      console.log(service);
      service.nearbySearch(request, nearbyCallback);
    }

    // Handle the results (up to 20) of the Nearby Search
    function nearbyCallback(results, status) {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
        showPanel(results);

      }
    }


    function showPanel(placeResult) {
        console.log(placeResult);
        var location="<h2> Nearby Shops with {{ $drink[0]['strDrink']}} </h2>";
        location += "<h5>"+ placeResult[0].name +"<br></h5>";
        location += "<h5>"+ placeResult[1].name +"<br></h5>";
        location += "<h5>"+ placeResult[2].name +"<br></h5>";
        location += "<h5>"+ placeResult[3].name +"<br></h5>";
        location += "<h5>"+ placeResult[4].name +"<br></h5>";
        location += "<h5>"+ placeResult[5].name +"</h5>";
        document.getElementById('panel').innerHTML = location;
      }

  </script>

  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfmNYNS8DPcugR-gQWFqNobagCwetRn70&libraries=places&callback=initMap">
  </script> 
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/posts/create.js')}}"></script>
@endsection