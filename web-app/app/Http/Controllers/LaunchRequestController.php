<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaunchRequestController extends Controller
{
    public function create()
    {
        return view('launch');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:50',
            'requester' => 'required|string|max:50',
            'contact' => 'required|string|max:20|min:8',
        ]);

        auth()->user()->launchRequests()->create($validatedData);

        return redirect('/')->with('message', 'Launch Request Created Successfully');
    }
}
