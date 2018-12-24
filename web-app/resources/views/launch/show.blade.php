@extends('layouts.withnav')

@section('content')
<div class="padding"></div>


<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1>Application Status</h1>
            <hr>
            @include('layouts.errors')
        </div>
    </div>
</div>
    
@endsection