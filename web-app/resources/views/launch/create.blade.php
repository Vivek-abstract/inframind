@extends('layouts.withnav') 
@section('content')

<div class="padding"></div>


<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1>Enter your Details</h1>
            <hr>
            @include('layouts.errors')

            <form action="/launch" method="post">
                @csrf

                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" name="company_name" placeholder="Company Name" 
                        value="{{old('company_name')}}" required>
                </div>

                <div class="form-group">
                    <label for="requester">Request initiated by</label>
                    <input type="text" class="form-control" name="requester" value="{{auth()->user()->name}}" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact No</label>
                    <input type="text" class="form-control" name="contact" placeholder="Contact No" 
                        value="{{old('contact')}}" required>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Launch Synergy</button>
            

            </form>
        </div>
    </div>
</div>
@endsection