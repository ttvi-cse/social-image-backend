<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/20/2016
 * Time: 4:09 PM
 */

Route::api(['version' => 'v1', 'before' => 'api.perm'], function() {

    Route::post('/user/register', [
        'uses' => 'API\V1\UserController@register',
        'as' => 'api.user.register'
    ]);
    Route::post('/user/login', [
        'uses' => 'API\V1\UserController@login',
        'as'   => 'api.login',
    ]);
    Route::post('/user/logout', [
        'uses' => 'API\V1\UserController@logout',
        'as' => 'api.user.logout'
    ]);

    Route::get('users/{users}/avatar', [
        'uses' => 'API\V1\UserController@avatar',
        'as'   => 'api.users.avatar'
    ]);

    Route::post('users/{users}/avatar', [
        'uses' => 'API\V1\UserController@avatar',
        'as'   => 'api.users.avatar.update',
        'protected' => true
    ]);

    Route::post('users/actions', [
        'uses' 		=> 'API\V1\UserController@actions',
        'as'   		=> 'api.users.actions',
        'protected' => true
    ]);

    Route::post('users/{users}', [
        'uses' 		=> 'API\V1\UserController@update',
        'as'   		=> 'api.users.update',
        'protected' => true
    ]);

    Route::resource('users', 'API\V1\UserController', [
        'only' => ['index', 'show']
    ]);

    /**
     * Posts
     */

    Route::resource('posts', 'API\V1\PostController', [
        'only' => ['index', 'show', 'store', 'update', 'destroy' ]
    ]);

    Route::get('posts/location/all', [
        'uses' => 'API\V1\PostController@all',
        'as'   => 'api.posts.all'
    ]);

    Route::get('posts/location/{location}', [
        'uses' => 'API\V1\PostController@location',
        'as'   => 'api.posts.location'
    ]);

    Route::get('posts/{posts}/comments', [
        'uses' => 'API\V1\PostController@comments',
        'as'   => 'api.posts.comments.index'
    ]);

    Route::post('posts/{posts}/comments', [
        'uses' 		=> 'API\V1\PostController@comments',
        'as'   		=> 'api.posts.comments.update',
        'protected' => true
    ]);

    Route::delete('posts/{posts}/comments/{comments}', [
        'uses' 		=> 'API\V1\PostController@destroy_comment',
        'as'   		=> 'api.posts.comments.destroy',
        'protected' => true
    ]);

    Route::post('locations', [
        'uses' => 'API\V1\PostController@locations',
        'as' => 'api.posts.locations.add',
        'protected' => true
    ]);

    Route::get('locations', [
        'uses' => 'API\V1\PostController@locations',
        'as' => 'api.posts.locations.index',
        'protected' => true
    ]);

});