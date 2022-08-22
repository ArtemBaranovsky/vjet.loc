# V-jet test project



## Steps to set up the project 

- composer install
- cp .env.example .env (you should provide your own mailtrap credentials or just use log to catch email)
- php artisan migrate
- php artisan db:seed

## Testing
- run in terminal: php vendor/bin/phpunit
