<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top blackNav" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="/">TIS</a>
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
                <li class="nav-item">
                    <a class="nav-link" href="/launch">Launch Requests</a>
                </li>

                @if(auth()->check())

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                          {{ Auth::user()->name }}
                        </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <form action="/logout" method="post">
                            @csrf
                            <button class="dropdown-item">Logout</a>
                        </form>
                    </div>
                </li>

                @else

                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Register</a>
                </li>

                @endif
            </ul>
        </div>
    </div>
</nav>