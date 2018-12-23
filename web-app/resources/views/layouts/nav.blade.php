<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="/">Technocratic Info Solutions</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>

                @if(auth()->check())

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                          {{ Auth::user()->name }}
                        </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/posts/create">Create Post</a>
    
                        @if (auth()->user()->isAdmin())
                            <a class="dropdown-item" href="/verify">Activate Posts</a>

                            @if (Request::is('posts/*'))
                                <a class="dropdown-item" href="/posts/{{$post->url_title}}/edit">Edit Post</a>
                            @endif
                        @endif
                        <a class="dropdown-item" href="/logout">Logout</a>
                    </div>
                </li>
                
                @else

                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>

                @endif
            </ul>
        </div>
    </div>
</nav>