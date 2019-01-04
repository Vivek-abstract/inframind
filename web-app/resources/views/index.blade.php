@extends('layouts.master') 
@section('hero-image')

<header class="masthead">
    <div class="jumbotron jumbotron-fluid vertical-center" style="background-image: url('/img/home-bg.jpg')">
        <div class="overlay"></div>
        <div class="container text-center dont-overlay">
            <div class="inner-container">
                <h1 class="display-4 heading">Technocratic Info Solutions</h1>
                <p class="no-margin">We are the leading cloud architecture provider for your application</p>
                <a class="btn btn-primary mt-4" href="/launch/create" role="button">Launch Synergy</a>
            </div>
        </div>
    </div>
</header>
@endsection