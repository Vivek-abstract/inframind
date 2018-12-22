from paramiko import SSHClient, AutoAddPolicy
from scp import SCPClient
import boto3
import fileinput

ec2_client = boto3.client('ec2')


def get_database_ip(stackName):
    """Returns IP Address of Database server"""

    custom = [
        {
            'Name': 'tag:Name',
            'Values': ['Database Server']
        },
        {
            'Name': 'tag:aws:cloudformation:stack-name',
            'Values': [stackName]
        }
    ]

    reservation = ec2_client.describe_instances(
        Filters=custom).get('Reservations')

    return reservation[0]['Instances'][0]['PublicIpAddress']


def get_ws1_ip(stackName):
    """Returns IP Address of Web Server 1"""

    custom = [
        {
            'Name': 'tag:Name',
            'Values': ['Web Server 1']
        },
        {
            'Name': 'tag:aws:cloudformation:stack-name',
            'Values': [stackName]
        }
    ]

    reservation = ec2_client.describe_instances(
        Filters=custom).get('Reservations')

    return reservation[0]['Instances'][0]['PublicIpAddress']

def get_ws2_ip(stackName):
    """Returns IP Address of Web Server 1"""

    custom = [
        {
            'Name': 'tag:Name',
            'Values': ['Web Server 2']
        },
        {
            'Name': 'tag:aws:cloudformation:stack-name',
            'Values': [stackName]
        }
    ]

    reservation = ec2_client.describe_instances(
        Filters=custom).get('Reservations')

    return reservation[0]['Instances'][0]['PublicIpAddress']

def modify(stackName):
    """Modify the connect.inc.php in the two web servers in the given stack"""

    print("Fetching IP Addresses")

    database_server_ip = get_database_ip(stackName)
    webserver1_ip = get_ws1_ip(stackName)
    webserver2_ip = get_ws2_ip(stackName)

    print("Database Server IP: {}".format(database_server_ip))
    print("Web Server 1 IP: {}".format(webserver1_ip))
    print("Web Server 2 IP: {}".format(webserver2_ip))

    # Now to create a custom connect.inc.php in local server
    for line in fileinput.input("../synergy/includes/connect.inc.php", inplace=True):
        if '$servername =' in line:
            print('$servername = "{}";'.format(database_server_ip))
        else:
            print(line, end="")

    print("New Connect.inc.php file created")

    # Copy this file and paste in web server 1
    print("Copying File to Web Server 1")

    ssh = SSHClient()
    ssh.set_missing_host_key_policy(AutoAddPolicy()) 
    ssh.load_system_host_keys()
    ssh.connect(webserver1_ip, username='ubuntu',
                key_filename='../../../.ssh/inframindwebserver.pem')

    scp = SCPClient(ssh.get_transport())
    scp.put('../synergy/includes/connect.inc.php',
            '/var/www/html/inframind/synergy/includes')

    ssh.close()

    print("File Copied to Web Server 1")

    # Web server 2
    print("Copying File to Web Server 1")

    ssh.connect(webserver2_ip, username='ubuntu',
                key_filename='../../../.ssh/inframindwebserver.pem')
    scp = SCPClient(ssh.get_transport())
    scp.put('../synergy/includes/connect.inc.php',
            '/var/www/html/inframind/synergy/includes')

    ssh.close()

    print("File Copied to Web Server 1")