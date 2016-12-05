<?php

Route::model('users', 'User');
Route::model('posts', 'Post');
Route::model('comments', 'Comment');
Route::model('vendors', 'Vendor');

Route::bind('posts', function($value, $route)
{
    $value = str_replace("posts://", "", $value);
    $post = Post::find($value);

    if(!$post){
        App::abort(404);
    }

    return $post;
});
