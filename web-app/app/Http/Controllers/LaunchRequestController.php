<?php

namespace App\Http\Controllers;

use App\LaunchRequest;
use Illuminate\Http\Request;

class LaunchRequestController extends Controller
{
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

        echo '<code>';

        while (!feof($proc)) {
            $live_output = fread($proc, 4096);
            $complete_output = $complete_output . $live_output;
            echo "<p>$live_output<p>";
            @flush();
        }
        echo '</code>';

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
        // $result = $this->liveExecuteCommand("cd ../../python-script && python3 -u main.py");

        $output = "
        Sending request to create stack: inframind1181
        Stack Create Request sent
        Waiter created
        Waiting...
        Stack created successfully
        Fetching IP Addresses
        Database Server IP: 10.0.1.164
        Web Server 1 IP: 3.16.66.132
        Web Server 2 IP: 13.58.194.150
        New Connect.inc.php file created
        Copying File to Web Server 1
        File Copied to Web Server 1
        Copying File to Web Server 2
        File Copied to Web Server 2
        Fetching DNS name....
        Your Application is live at inframind-LoadBala-OZ97LEFR2JO8-68894473.us-east-2.elb.amazonaws.com
        "; 
        
        $databaseIP = '';
        $webServerIp1 = '';
        $webServerIp2 = '';
        $dnsName = '';

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
           
       
            if (strpos($line, 'Database Server IP')) {
                $line = preg_replace('/\s+/', '', $line);
                $databaseIP = explode(':', $line)[1];
            } else if (strpos($line, 'Web Server 1 IP')) {
                $line = preg_replace('/\s+/', '', $line);
                $webServerIp1 = explode(':', $line)[1];
            } else if (strpos($line, 'Web Server 2 IP')) {
                $line = preg_replace('/\s+/', '', $line);
                $webServerIp2 = explode(':', $line)[1];
            } else if (strpos($line, 'Your Application is live')) {
                $line = trim($line);
                $dnsName = explode(" ", $line)[5];
            }
        }

        dd($databaseIP, $webServerIp1, $webServerIp2, $dnsName);

        if ($result['exit_status'] === 0) {
            $output = $result['output'];

            dd($output);
            // return view('launch.show');
        } else {
            dd("Failure");

            // return view('launch.show')->with('errors', [])
        }

    }
}
