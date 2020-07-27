## Shopping Cart API

This app was developed with [Laravel  7.0](https://laravel.com/docs/7.x) and will handle the communication with all the 
clients that needs to consume it. Returning the required information about the Shopping Cart.

## Server Requirements

If you are not using Homestead, you will need to make sure your server meets the following requirements:

- PHP >= 7.2.5
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation
- Clone the project

    ```git clone https://github.com/jhonnar-rodriguez/evertec-shopping-cart.git```

- Move to the project folder

    ```cd evertec-shopping-cart```
 
- Copy .env.example and rename it to .env

    ```cp .env.example .env```
 
- Install Dependencies

    ```composer install```

- Generate the application key

    ```php artisan key:generate```

- Setup your credentials in the .env file

- Run Migrations

    ```php artisan migrate```

- Install Laravel Passport

    ```php artisan passport:install```

- Enable Storage Folder

    ```php artisan storage:link```

- Run Seeders

    ```php artisan db:seed```
    
## API Documentation
All API End points and documentation can be found [here](https://documenter.getpostman.com/view/3838871/T1DqgH71?version=latest)

## Required Credentials
Write me an email to jhonnar.rodriguez@gmail.com in order to generate the required credentials. Some of them are:
- PLACE_TO_PAY_LOGIN=Login
- PLACE_TO_PAY_SECRET_KEY=TranKey
- PLACE_TO_PAY_BASE_URL=Testing Service
- FRONTEND_URL=Your Client URL
