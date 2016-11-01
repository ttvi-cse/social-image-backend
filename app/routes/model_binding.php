<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/20/2016
 * Time: 4:19 PM
 */

Route::model('users', 'User');
Route::model('news', 'Article');
Route::model('lessons', 'Article');
Route::model('discussions', 'Discussion');
Route::model('events', 'MyEvent');
Route::model('comments', 'Comment');
Route::model('tags', 'Tag');
Route::model('androidapps', 'Androidapp');
Route::model('questions', 'Question');
Route::model('classes', 'MyClass');
Route::model('resources', 'Media');
Route::model('announcements', 'Announcement');
Route::model('assessments', 'Article');
Route::model('surveys', 'Article');
Route::model('questions', 'Question');

Route::bind('articles', function($value, $route)
{
    $value = str_replace("article://", "", $value);
    $article = Article::find($value);

    if(!$article){
        App::abort(404);
    }

    return $article;
});

Route::bind('news', function($value, $route)
{
    $value = str_replace("news://", "", $value);
    $news = Article::find($value);

    if(!$news){
        App::abort(404);
    }

    return $news;
});

Route::bind('lessons', function($value, $route)
{
    $value = str_replace("lesson://", "", $value);
    $lesson = Article::find($value);

    if(!$lesson){
        App::abort(404);
    }

    return $lesson;
});
