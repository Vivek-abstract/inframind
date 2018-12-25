import boto3, sys

stackName = sys.argv[1]
client = boto3.client('cloudformation')

client.delete_stack(StackName=stackName)
