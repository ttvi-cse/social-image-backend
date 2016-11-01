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
//    Route::get('/posts', [
//        'uses' => 'API\V1\PostController@index',
//        'as' => 'api.post.index'
//    ]);
//    Route::post('/posts', [
//        'uses' => 'API\V1\PostController@create',
//        'as' => 'api.post.create'
//    ]);
//    Route::get('/posts/{post_id}', [
//        'uses' => 'API\V1\PostController@show',
//        'as' => 'api.post.detail'
//    ]);
//    Route::post('/posts/{post_id}/rate', [
//        'uses' => 'API\V1\PostController@rate',
//        'as' => 'api.post.rate'
//    ]);
//    Route::post('/posts/{post_id}/like', [
//        'uses' => 'API\V1\PostController@like',
//        'as' => 'api.post.like'
//    ]);
//    Route::get('/posts/{post_id}/comments', [
//        'uses' => 'API\V1\CommentController@index',
//        'as' => 'api.comment.index'
//    ]);
//    Route::post('/posts/{post_id}/comments', [
//        'uses' => 'API\V1\CommentController@create',
//        'as' => 'api.comment.create'
//    ]);
    Route::resource('posts', 'API\V1\PostController', [
        'only' => ['index', 'show']
    ]);
});