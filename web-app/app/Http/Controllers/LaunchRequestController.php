<?php

namespace App\Http\Controllers;

use App\LaunchRequest;
use Illuminate\Http\Request;

class LaunchRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $launchRequests = LaunchRequest::all();
        } else {
            $launchRequests = LaunchRequest::where('user_id', auth()->id())->get();
        }
        return view('launch.index', compact('launchRequests'));
    }

    public function create()
    {
        return view('launch.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:50',
            'requester' => 'required|string|max:50',
            'contact' => 'required|string|max:20|min:8',
        ]);

        $launchRequest = auth()->user()->launchRequests()->create($validatedData);

        return redirect("/launch/$launchRequest->id")->with('message', 'Launch Request Created Successfully');
    }

    public function show(LaunchRequest $launchRequest)
    {
        return view('launch.show', compact('launchRequest'));
    }

    public function destroy(LaunchRequest $launchRequest)
    {
        shell_exec("cd ../../python-script && python3 delete-stack.py $launchRequest->stack_name");
        $launchRequest->delete();
        return redirect('/launch')->with('message', 'App Deleted Successfully');
    }
}
