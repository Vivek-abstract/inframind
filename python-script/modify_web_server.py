from paramiko import SSHClient, AutoAddPolicy
from scp import SCPClient
import boto3
import fileinput
import getpass

ec2_client = boto3.client('ec2')


def get_database_ip(stackName):
    """Returns Private IP Address of Database server"""

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

    return reservation[0]['Instances'][0]['PrivateIpAddress']


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
    """Returns IP Address of Web Server 2"""

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

def get_elb_dns_name(stackName):
    """Returns the DNS Name of Load Balancer"""
    
    client = boto3.client('cloudformation')

    resource = client.describe_stack_resource(StackName=stackName, LogicalResourceId='LoadBalancer')
    physical_address = resource['StackResourceDetail']['PhysicalResourceId']

    elb_client = boto3.client('elb')
    elb = elb_client.describe_load_balancers(LoadBalancerNames=[physical_address])
    
    return elb['LoadBalancerDescriptions'][0]['DNSName']

def copy_file_to_webserver(ip):
    """Copy the connect.inc.php file to the web server with IP address = ip"""
    ssh = SSHClient()
    username = getpass.getuser()

    ssh.set_missing_host_key_policy(AutoAddPolicy()) 
    ssh.load_system_host_keys()

    key_path= "/home/" + username + "/.ssh/inframindwebserver.pem"

    ssh.connect(ip, username='ubuntu', key_filename=key_path)

    scp = SCPClient(ssh.get_transport())
    scp.put('../synergy/includes/connect.inc.php',
            '/var/www/html/inframind/synergy/includes')

    ssh.close()


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

    print("Copying File to Web Server 1")

    
    copy_file_to_webserver(webserver1_ip)

    print("File Copied to Web Server 1")

    print("Copying File to Web Server 2")

    copy_file_to_webserver(webserver2_ip)

    print("File Copied to Web Server 2")

    print("Fetching DNS name....")
    dns_name = get_elb_dns_name(stackName)
    print("Your Application is live at {}".format(dns_name))
