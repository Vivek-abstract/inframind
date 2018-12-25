@extends('layouts.withnav') 
@section('content')

<div class="padding"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Your Launch Requests</h4>
            <div class="table-responsive mt-4">
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>
                        <th>App Name</th>
                        <th>Requester Name</th>
                        <th>Contact</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($launchRequests as $launchRequest)
                        <tr>
                            <td>{{ $launchRequest->stack_name }}</td>
                            <td>{{ $launchRequest->requester }}</td>
                            <td>{{ $launchRequest->contact }}</td>
                            <td><a href="/launch/{{ $launchRequest->id }}" class="link">Check Status</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <a href='/launch/create' class="btn btn-primary text-center">Launch New App</a>
            </div>
        </div>

    </div>
</div>
@endsection