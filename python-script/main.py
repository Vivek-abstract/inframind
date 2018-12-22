import boto3
import random
from modify_web_server import modify

client = boto3.client('cloudformation')

stackName = "inframind" + str(random.randint(0, 10000))
template = open('cloudformation-template', 'r')

templateURL = "https://s3.us-east-2.amazonaws.com/cf-templates-1lfybsm6ut9us-us-east-2/2018356IDJ-CloudFormation%20template%20inframindnp4hu2a7f8s"

client.create_stack(
    StackName=stackName,
    TemplateURL=templateURL,
    ClientRequestToken=stackName
)

print("Stack Create Request sent")

waiter = client.get_waiter('stack_create_complete')
print("Waiter created")

print("Waiting...")

waiter.wait(
    StackName=stackName,
)

print("Stack created successfully")

modify(stackName)