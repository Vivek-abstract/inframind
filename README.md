# Inframind 2019 - Cloud Computing Solution

Our project consists of the following folders:
- python-script: Contains all the python scripts required for automating the process of deploying Synergy.
- synergy: Contains the Synergy application which needs to be deployed.
- web-app: Our web application, made using Laravel - It consists of our user interface and calls the python-scripts in the backend. It stores all the launch requests created in a launch_requests table in MySQL.
- lambda-functions: Contains all the lambda functions for automatic startup, shutdown and backup.

## Installation

1. Install PHP, MySQL, Composer and Laravel.
2. Install the AWS CLI.
3. Run "aws configure" in a terminal. Make sure the IAM user has administrator access or the necessary permissions to launch ec2 instances, load balancers, etc.
4. Launch AWS Cloudformation and click design a stack.
5. Upload the cloudformation-template which is present in python-script folder.
6. Copy the S3 bucket URL of the template and paste it in templateURL="" inside main.py
7. Launch a terminal in the web-app directory.
8. Go to the .env file inside web-app/ folder and modify the DB_USERNAME and DB_PASSWORD to the MySQL username and password on your system.
9. Run "php artisan migrate" in the terminal to migrate the database.
10. Run "php artisan serve" inside the terminal to serve the application.
11. Go to localhost:8000 to view the application.