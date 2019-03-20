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

| Method | Endpoint                                              | Description                  | Access          |
|--------|-------------------------------------------------------|------------------------------|-----------------|
| GET    | /api/albums                                           | gets all albums              | logged in users |
| POST   | /api/albums                                           | creates new album            | artists/admin   |
| GET    | /api/albums/{album}                                   | gets single album            | logged in users |
| PATCH  | /api/albums/{album}                                   | updates album                | artists/admin   |
| DELETE | /api/albums/{album}                                   | deletes album                | artists/admin   |
| POST   | /api/albums/{album}/tracks                            | creates new track            | artists/admin   |
| GET    | /api/albums/{album}/tracks                            | gets all album tracks        | logged in users |
| DELETE | /api/albums/{album}/tracks/{track}                    | deletes track                | artists/admin   |
| PATCH  | /api/albums/{album}/tracks/{track}                    | updates track                | artists/admin   |
| GET    | /api/playlists                                        | get all playlists            | logged in users |
| POST   | /api/playlists                                        | create playlist              | logged in users |
| POST   | /api/playlists/{playlist}/tracks/{track}              | add track to playlist        | logged in users |
| GET    | /api/playlists/{playlist}/tracks                      | get all tracks from playlist | logged in users |
| POST   | /api/albums/{album}/tracks/{track}/comments           | comment on track             | logged in users |
| GET    | albums/{album}/tracks/{track}/comments                | get all comments on track    | logged in users |
| GET    | /api/albums/{album}/tracks/{track}/comments/{comment} | get single comment           | logged in users |
| DELETE | /api/albums/{album}/tracks/{track}/comments/{comment} | delete comment               | logged in users |
| PATCH  | /api/albums/{album}/tracks/{track}/comments/{comment} | update comment               | logged in users |
| POST   | /api/genres                                           | create genre                 | artists/admin   |
| GET    | /api/genres                                           | get genres                   | logged in users |
| POST   | /api/register                                         | registers as user or artist  | all users       |
| POST   | /api/login                                            | logins in as user or artist  | all users       |
| POST   | /api/logout                                           | logs out logged in user      | logged in users |
| GET    | /api/profile                                          | logged user profile          | logged in users |
