import json, boto3

def lambda_handler(event, context):
    """Automatically starts all the synergy EC2 instances at 9am"""
    
    ec2client = boto3.client('ec2')
   
    instances = []
    
    
    running_filter = [
        {
            'Name': 'instance-state-name', 
            'Values': ['stopped']
        }, 
        {
            'Name': 'tag:app-name',
            'Values': ['synergy']
        }
    ]
    
    # Get all the stopped Synergy servers that need to be started
    response = ec2client.describe_instances(Filters=running_filter)
    
    for reservation in response["Reservations"]:
        for instance in reservation["Instances"]:
            instances.append(instance["InstanceId"])

    
    if len(instances):
        # Start the servers
        ec2client.start_instances(InstanceIds=instances)
        print('Started the following instances: ' + str(instances))
        return {
            'statusCode': 200,
            'body': json.dumps('Started the following instances: ' + str(instances))
        }
    else:
        print("No instances are stopped to be started")
        return {
            'statusCode': 200,
            'body': json.dumps('No instances are stopped to be started')
        }
        
    
