<nav class="navbar navbar-expand-lg navbar-dark bg-dark pl-4 pr-4">
    <a class="navbar-brand" href="/">
        <img src="https://www.pngkey.com/png/full/510-5109524_open-emoji-cerveja.png" width="40" height="40" class="d-inline-block align-top" alt="">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/comments">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/locate">Locate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/posts">Posts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/choose-topic">Write</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/user/">Profile</a>
            </li>
        </ul>

        <ul class="navbar-nav">
              <div class="collapse navbar-collapse pr-5" id="navbar-list-4">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropleft">
                        <a class="nav-link dropdown-toggle dropleft" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="https://img.icons8.com/bubbles/100/000000/user.png" width="40" height="40" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropleft" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Dashboard</a>
                            <a class="dropdown-item" href="/user/profile/edit">Edit Profile</a>
                            
                            <a class="dropdown-item dropleft" href="/logout">
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log out') }}
                                    </x-dropdown-link>
                                </form>
                            </a>
                        </div>
                    </li>   
                </ul>
              </div>
        </ul>

        
      {{-- <form class="form-inline my-2 my-lg-0">
        <a class="nav-link" href="#">Locate</a>
        {{-- <input class="form-control mr-sm-2" type="search" placeholder="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> --}}
      {{-- </form> --}} 
    </div>
  </nav>