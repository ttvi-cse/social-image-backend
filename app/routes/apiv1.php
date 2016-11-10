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
    Route::post('/users/{user_id}/profile', [
        'uses' => 'API\V1\UserController@profile',
        'as' => 'api.user.profile'
    ]);

    /**
     * Posts
     */

    Route::resource('posts', 'API\V1\PostController', [
        'only' => ['index', 'show', 'store', 'update', 'destroy' ]
    ]);
});