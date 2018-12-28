@extends('layouts.withnav') 
@section('content')
<div class="padding"></div>


<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1>Application Status</h1>
            <hr>
            @include('layouts.errors')

            <pre class="language-python"><code>@if ($launchRequest->output){{ substr($launchRequest->output, 0) }}                        
                    @else {{ auth()->user()->runScriptAndShowOutput($launchRequest) }}
                    @endif</code></pre>

            <h4>Status: <span class="green">{{$launchRequest->status}}</span></h4>

            @if($launchRequest->status === 'Success')
                <h4>Web Server 1 IP: <a class="text-info underline" href="http://{{$launchRequest->ws1_ip}}" target="_blank">{{$launchRequest->ws1_ip}}<a></h4>
                <h4>Web Server 2 IP: <a class="text-info underline" href="http://{{$launchRequest->ws2_ip}}" target="_blank">{{$launchRequest->ws2_ip}}<a></h4>
                <h4>Database Server Private IP: <span class="text-info">{{$launchRequest->database_ip}}</span></h4>
                <div class="row mt-4 mb-5 ml-1">
                    <a href="http://{{$launchRequest->dns_name}}" class="btn btn-success" target="_blank">View Application</a>
                    <form action="/launch/{{$launchRequest->id}}" method='POST'>
                        @method('DELETE') @csrf
                        <button type="submit" class="btn btn-danger ml-4">Delete Application</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection