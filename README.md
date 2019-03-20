[![Build Status](https://travis-ci.org/Sebuliba-Adrian/BeatsApp.svg?branch=master)](https://travis-ci.org/Sebuliba-Adrian/BeatsApp)
[![Coverage Status](https://coveralls.io/repos/github/Sebuliba-Adrian/BeatsApp/badge.svg)](https://coveralls.io/github/Sebuliba-Adrian/BeatsApp)
# BeatsApp

## Requirements

[`PHP 7.2`](http://php.net/manual/en/install.php) - This version of Laravel uses PHP 7.2

[`Composer`](https://getcomposer.org/) - Composer is required for the libraries and dependencies

##Clone 
```git clone https://github.com/Sebuliba-Adrian/BeatsApp.git```

## Installation

Install all the required libraries from Composer while in the BeatsApp folder
```
composer install
```
For the app to connect to you local database, you need to create a `.env` file on the root of your project.

To do that, copy the `.env.example` and rename it to `.env`, and then fill in the
necessary configurations as shown below
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=<database name>
DB_USERNAME=<database username>
DB_PASSWORD=<database password>
DB_SOCKET=<full path to the socket if required>
```

Generate an Artisan Key
```
php artisan key:generate
```
Generate Jwt key 
```
php artisan jwt:secret
```

Run migrations to create tables.
```
php artisan migrate
```

## BeatsApp api Endpoints

| Methood | Endpoint                        | Description                        | Returns                                   |
|---------|---------------------------------|------------------------------------|-------------------------------------------|
| GET     |    /api/albums                  | Get all albums                     | list of Albums                           |
| POST    |    /api/albums                  | Create a new Album                 | Album                                     |
| GET     |    /api/albums/{album}          | Get a Album                        | Album       , with Artist info and Genres |
| PATCH   |    /api/albums/{album}          | Update an Album                    | Album       , with Artist info and Genres |
| DELETE  |    /api/albums/{album}          | Delete an Album                    | Confirmation array                        |
| GET     |    /api/albums/{album}/tracks   | Get all tracks for a certain album |   |
