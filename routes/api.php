<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('logout', 'UserController@logout');
Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::post('profile', 'UserController@details');

        Route::post('genres', 'GenreController@store')->middleware('is_artist');
        Route::get('genres', 'GenreController@index');
        Route::delete('genres/{genre}', 'GenreController@destroy')->middleware('is_artist');

        Route::post('albums', 'AlbumController@store')
        ->middleware('is_artist');
        Route::get('albums', 'AlbumController@index');
        Route::get('albums/{album}', 'AlbumController@show');
        Route::delete('albums/{album}', 'AlbumController@destroy')->middleware('is_artist');
        Route::patch('albums/{album}', 'AlbumController@update')->middleware('is_artist');

        Route::post('albums/{album}/tracks', 'TrackController@store')
        ->middleware('is_artist');
        Route::get('albums/{album}/tracks', 'TrackController@index');
        Route::delete('albums/{album}/tracks/{track}', 'TrackController@destroy')
        ->middleware('is_artist');
        Route::patch('albums/{album}/tracks/{track}', 'TrackController@update')->middleware('is_artist');
        Route::get('tracks/{track}', 'TrackController@show');


        Route::get('playlists', 'PlaylistController@index');
        Route::post('playlists/{playlist}/tracks/{track}', 'PlaylistController@add');
        Route::get('playlists/{playlist}/tracks', 'PlaylistController@show');

        Route::post('albums/{album}/tracks/{track}/comments', 'CommentController@store');
        Route::get('albums/{album}/tracks/{track}/comments', 'CommentController@index');
        Route::get('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@show');
        Route::delete('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@destroy')->middleware('is_artist');
        Route::patch('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@update');
    }
);

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);
