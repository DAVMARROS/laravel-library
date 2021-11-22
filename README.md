<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://media-exp1.licdn.com/dms/image/C4E0BAQEFxDKy5rsfXA/company-logo_200_200/0/1618508725292?e=2159024400&v=beta&t=hI8LyOhCzSfIgYGVDrPy1WQ6xI458N2xFVSKQ_1II3Q" width="200"></a></p>

<h2 align="center">Laravel Library</h2>

## Requirements
```
# PHP 7.4 or newer (Tested on 7.4.18)
# Laravel 8 or newer (Tested on 8.72.0)
# Node 12 or newer (Tested on 12.18.3)
```

## Clone the project and install dependencies

First you need to clone the project on your device and open the folder in terminal. Once the project is cloned, you have to install laravel and node packages and compile scripts.

```
# Install Laravel dependencies
composer install

# Install node packages
npm install

# Compile scripts
npm run dev
```

## Generate project key

```
php artisan key:generate
```

## Make .env file

Now, you need to make the .env file, you can use the .env.example and set the variables for the database conection. I recomend to set the mail variables so the system can send recovery mails. Example: 

```
# Database Variables
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library
DB_USERNAME=root
DB_PASSWORD=


# Mail variables
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=example@gmail.com
MAIL_PASSWORD="Application password"
MAIL_ENCRYPTION=tls
```

## Database configuration

Once you make the .env file you need to create database, the default is library, but you can change the name in the .env file. Then, you need to run migrations and seed your database. 

```
php artisan migrate --seed
```

## Server starts

Onces you install and configurates the aplication you have to start the local server.

```
php artisan serve
```

## User Admin
```
email: diana@twobits.com.mx
password: maniak.co
```

<h2>Enjoy</h2>
