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

            <h2>Status: <span class="green">{{$launchRequest->status}}</span></h2>
        </div>
    </div>
</div>
@endsection