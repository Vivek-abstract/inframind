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
                        <tr>
                            <td>Mohsin</td>
                            <td>Irshad</td>
                            <td>7039715240</td>
                            <td><a href="/launch" class="link">Check Status</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection