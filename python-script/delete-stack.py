import boto3

stackName = input("Enter stack name: ")
client = boto3.client('cloudformation')

client.delete_stack(StackName=stackName)

print("Deleting Stack...")
waiter = client.get_waiter('stack_delete_complete')
waiter.wait(StackName=stackName)

print("Stack {} Deleted successfully".format(stackName))