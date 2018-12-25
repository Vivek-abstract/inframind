<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->email == 'vivek040997@gmail.com';
    }

    public function launchRequests()
    {
        return $this->hasMany(LaunchRequest::class);
    }

    public function liveExecuteCommand($cmd)
    {

        while (@ob_end_flush()); // end all output buffers if any

        $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

        $live_output = "";
        $complete_output = "";

        while (!feof($proc)) {
            $live_output = ltrim(fread($proc, 4096));
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
            'output' => trim(str_replace("Exit status : " . $matches[0], '', $complete_output)),
        );
    }

    public function runScriptAndShowOutput($launchRequest)
    {
        $result = $this->liveExecuteCommand("cd ../../python-script && python3 -u main.py");
        $stackName = '';

        if ($result['exit_status'] === 0) {
            $output = $result['output'];

            $databaseIP = '';
            $webServerIp1 = '';
            $webServerIp2 = '';
            $dnsName = '';

            // Iterate output line by line
            foreach (preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
                if (strpos($line, 'Sending request to create stack') !== false) {
                    // Remove all whitespaces and split by :
                    $line = preg_replace('/\s+/', '', $line);
                    $stackName = explode(':', $line)[1];
                } else if (strpos($line, 'Database Server IP') !== false) {
                    $line = preg_replace('/\s+/', '', $line);
                    $databaseIP = explode(':', $line)[1];
                } else if (strpos($line, 'Web Server 1 IP') !== false) {
                    $line = preg_replace('/\s+/', '', $line);
                    $webServerIp1 = explode(':', $line)[1];
                } else if (strpos($line, 'Web Server 2 IP') !== false) {
                    $line = preg_replace('/\s+/', '', $line);
                    $webServerIp2 = explode(':', $line)[1];
                } else if (strpos($line, 'Your Application is live') !== false) {
                    $line = trim($line);
                    $dnsName = explode(" ", $line)[5];
                }
            }

            $launchRequest->update([
                'database_ip' => $databaseIP,
                'ws1_ip' => $webServerIp1,
                'ws2_ip' => $webServerIp2,
                'dns_name' => $dnsName,
                'output' => $output,
                'status' => 'Success',
                'stack_name' => $stackName
            ]);

            return "Success";
        } else {
            // Rollback the stack

            $launchRequest->update([
                'status' => 'Failed',
            ]);

            foreach (preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
                if (strpost($line, 'Sending request to create stack') !== false) {
                    // Remove all whitespaces and split by :
                    $line = preg_replace('/\s+/', '', $line);
                    $stackName = explode(':', $line)[1];
                    break;
                }
            }

            $result = $this->liveExecuteCommand("cd ../../python-script && python3 delete_stack.py $stackName");

            return "The script failed to execute";
        }
    }

}
