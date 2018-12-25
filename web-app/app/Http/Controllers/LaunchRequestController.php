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
        return view('launch.index');
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

    public function liveExecuteCommand($cmd)
    {

        while (@ob_end_flush()); // end all output buffers if any

        $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

        $live_output = "";
        $complete_output = "";


        while (!feof($proc)) {
            $live_output = fread($proc, 4096);
            $complete_output = $complete_output . $live_output;
            echo $live_output;
            @flush();
        }

        pclose($proc);

        // get exit status
        preg_match('/[0-9]+$/', $complete_output, $matches);


        // return exit status and intended output
        return array(
            'exit_status' => intval($matches[0]),
            'output' => str_replace("Exit status : " . $matches[0], '', $complete_output),
        );
    }

    public function show(LaunchRequest $launchRequest)
    {
        return view('launch.show', compact('launchRequest'));
    }
}
