# KEFIS INVENTORY SYSTEM
### KEFIS MONOLITH ARCHITECTURE
### Running the application;

1. Git clone the repository
2. cd into the root folder of the repository
3. run composer install to download the necessary dependencies
4. Edit the .env file with the correct database environment; username and root password
5. Run, "php artisan migrate" to run database migrations
6. Run "php artisan db:seed --class=ProductSeeder" to seed the test products for the application
7. Run "php artisan key:generate" to generate the application key
8. Run "php artisan serve" to start the application on your local machine.





