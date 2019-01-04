import json, boto3

def lambda_handler(event, context):
    """Automatically shuts down all the EC2 instances at 6pm"""
    
    ec2client = boto3.client('ec2')
   
    instances = []
    
    
    running_filter = [
        {
            'Name': 'instance-state-name', 
            'Values': ['running']
        }, 
        {
            'Name': 'tag:app-name',
            'Values': ['synergy']
        }
    ]
    
    # Get all the running Synergy servers that need to be stopped
    response = ec2client.describe_instances(Filters=running_filter)
    
    for reservation in response["Reservations"]:
        for instance in reservation["Instances"]:
            instances.append(instance["InstanceId"])

    
    if len(instances):
        # Stop the servers
        ec2client.stop_instances(InstanceIds=instances)
        print('Stopped the following instances: ' + str(instances))
        return {
            'statusCode': 200,
            'body': json.dumps('Stopped the following instances: ' + str(instances))
        }
    else:
        print("No instances running")
        return {
            'statusCode': 200,
            'body': json.dumps('No instances running')
        }
        
    
