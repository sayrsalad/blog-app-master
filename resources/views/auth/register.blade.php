<x-guest-layout>
    <div class="lg:flex">
        <div class="lg:w-1/2 xl:max-w-screen-sm">
            <div class="py-12 bg-indigo-100 lg:bg-white flex justify-center lg:justify-start lg:px-12">
                <div class="cursor-pointer flex items-center">
                    <div>
                        <img class="w-10 text-indigo-500" src="https://cdn-icons-png.flaticon.com/512/931/931949.png" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 225 225" style="enable-background:new 0 0 225 225;" xml:space="preserve" />
                    </div>
                    <div class="text-2xl tracking-wide ml-2 font-semibold">Alcohol Blog Post</div>
                </div>
            </div>
            <div class="mt-5 px-12 sm:px-24 md:px-48 lg:px-12 lg:mt-8 xl:px-24 xl:max-w-2xl">
                <h2 class="text-center text-4xl text-indigo-900 font-display font-semibold lg:text-left xl:text-5xl
                    xl:text-bold">Register</h2>
                <div class="mt-12">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfmNYNS8DPcugR-gQWFqNobagCwetRn70&libraries=places"></script>
                        <script type="text/javascript">
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
                        </script>
                        <div>
                            <x-label for="name" :value="__('Name')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="name" type="text" name="name" :value="old('name')" required autofocus>
                        </div>

                        <div class="mt-4">
                            <x-label for="email" :value="__('Email Address')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="email" type="email" name="email" :value="old('email')" required autofocus>
                        </div>

                        <div class="mt-4">
                            <x-label for="location" :value="__('Location')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="location" type="text" name="location" :value="old('location')" required autofocus>
                            <label id="lblresult"></label>
                        </div>

                        <div class="mt-4">
                            <x-label for="password" :value="__('Password')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="password" type="password" name="password" :value="old('password')" required autofocus>
                            <label id="lblresult"></label>
                        </div>

                        <div class="mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="password_confirmation" type="password" name="password_confirmation" :value="old('password_confirmation')" required autofocus>
                            <label id="lblresult"></label>
                        </div>

                        <div class="mt-10">
                            <button class="bg-indigo-500 text-gray-100 p-4 w-full rounded-full tracking-wide
                                font-semibold font-display focus:outline-none focus:shadow-outline hover:bg-indigo-600
                                shadow-lg" type="submit">
                                Register
                            </button>
                        </div>
                    </form>
                    <div class="mt-12 text-sm font-display font-semibold text-gray-700 text-center">
                        Already have an account ? <a class="cursor-pointer text-indigo-600 hover:text-indigo-800" href="{{ route('login') }}">Sign in</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex items-center justify-center bg-indigo-100 flex-1 h-screen" style="background-image: url(https://images.unsplash.com/photo-1508253730651-e5ace80a7025?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80); background-size: cover;">
        </div>
    </div>
</x-guest-layout>