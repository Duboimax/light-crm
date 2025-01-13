# Light Crm Back end

### How install project

1. Clone the project
2. Open ```light-crm``` folder
3. Put ```docker compose -up -d```
4. Go to ```back``` folder
5. Run ```composer install``` to install dependencies
6. Run ```php bin/console d:d:c``` if database isn't already created
7. Run ```php bin/console d:m:m``` for load migrations and create tables
8. Run ```php bin/console d:f:l``` for load fixtures
9. In ```config``` folder create ```jwt``` folder
10. Run ```php bin/console lexik:jwt:generate-keypair ```to generate key for jwt auth
11. Run ```symfony server:start``` to start server

Nice you have already installed light-crm back and you are ready to code :)

### Tips

if you want to visualize your database, you can use ```TablePlus```
