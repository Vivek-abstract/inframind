import json, boto3

def lambda_handler(event, context):
    """
    Creates a backup of the Synergy servers at 6pm everyday.
    Only stores the 2 latest backups of each server and automatically
    deletes the oldest backup.
    """
    
    ec2client = boto3.client('ec2')
    
    volumes = []
    instances = []
    
    running_filter = [
        {
            'Name': 'instance-state-name',
            'Values': ['running', 'stopped', 'pending', 'stopping', 'stopped']
        },
        {
            'Name': 'tag:app-name',
            'Values': ['synergy']
        }
    ]

    # Get the running Synergy Servers
    
    response = ec2client.describe_instances(Filters=running_filter)
    
    for reservation in response["Reservations"]:
        for instance in reservation["Instances"]:
            instances.append(instance['InstanceId'])
            for volume in instance['BlockDeviceMappings']:
                volumes.append(volume['Ebs']['VolumeId'])
    
    
    print("Volumes present in EC2: {}".format(volumes))
    
    for volume in volumes:
        print("Backing up {}".format(volume))
        backup_filter = [
            {
                'Name': 'volume-id',
                'Values': [volume]
            },
        ]
    
        # Get the previous backups of this volume
        res = ec2client.describe_snapshots(Filters=backup_filter)
    
        snapshots = res['Snapshots']

        # Check if more than two backups
        if len(snapshots) >= 2:
            # Delete the oldest snapshot
    
            print("Already 2 snapshots present. Finding older snapshot to delete.....")
    
            min_snap_time = snapshots[0]['StartTime']
            min_index = 0
    
            for i in range(1, len(snapshots)):
                if snapshots[i]['StartTime'] < min_snap_time:
                    min_snap_time = snapshots[i]['StartTime']
                    min_index = i
    
            snapshot_to_delete = snapshots[min_index]['SnapshotId']
    
            print("Deleting Snapshot {}".format(snapshot_to_delete))
    
            ec2client.delete_snapshot(SnapshotId=snapshot_to_delete)
    
            print("Snapshot Deleted")
    
    
        # Create latest backup
        print("Creating Latest Snapshot of {}".format(volume))
    
        ec2client.create_snapshot(
            VolumeId=volume,
            TagSpecifications=[
                {
                    'ResourceType': 'snapshot',
                    'Tags': [
                        {
                            'Key': 'Name',
                            'Value': 'Backup of {}'.format(volume)
                        },
                    ]
                },
            ]
        )
    
        print("Volume: {} backed up successfully".format(volume))
            
    return {
        'statusCode': 200,
        'body': json.dumps('Backed up the following Instances: {}'.format(instances))
    }
