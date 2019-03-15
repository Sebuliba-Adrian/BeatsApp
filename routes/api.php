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
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('profile', 'UserController@details'); // user profile will be showed here

    Route::post('genres', 'GenreController@store')->middleware('is_artist'); //Store the genres here
    Route::get('genres', 'GenreController@index'); //Store the genres here
    Route::delete('genres/{genre}', 'GenreController@destroy')->middleware('is_artist'); //Store the genres here

    Route::post('albums', 'AlbumController@store')->middleware('is_artist'); //store albums
    Route::get('albums', 'AlbumController@index'); //show all available albums
    Route::get('albums/{album}', 'AlbumController@show'); //show a individual album
    Route::delete('albums/{album}', 'AlbumController@destroy')->middleware('is_artist'); //delete individual album
    Route::patch('albums/{album}', 'AlbumController@update')->middleware('is_artist'); //update an individual album

    Route::post('albums/{album}/tracks', 'TrackController@store')->middleware('is_artist'); //save track to album here
    Route::get('albums/{album}/tracks', 'TrackController@index'); //show all tracks in an album here
    Route::delete('albums/{album}/tracks/{track}', 'TrackController@destroy')->middleware('is_artist');//delete a track in an album here
    Route::patch('albums/{album}/tracks/{track}', 'TrackController@update')->middleware('is_artist'); //update track in an album here
    Route::get('tracks/{track}', 'TrackController@show'); //track download/play/view will happen here


    Route::get('playlists', 'PlaylistController@index'); // create playlist here
    Route::post('playlists', 'PlaylistController@store'); // create playlist here
    Route::post('playlists/{playlist}/tracks/{track}', 'PlaylistController@add');// add track to  playlist here
    Route::get('playlists/{playlist}/tracks', 'PlaylistController@show')->middleware('is_artist'); //display play list here

    Route::post('albums/{album}/tracks/{track}/comments', 'CommentController@store');
    Route::get('albums/{album}/tracks/{track}/comments', 'CommentController@index');
    Route::get('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@show');
    Route::delete('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@destroy')->middleware('is_artist');
    Route::patch('albums/{album}/tracks/{track}/comments/{comment}', 'CommentController@update');


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

