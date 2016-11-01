<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 6:59 PM
 */
class CommentObserver
{
    public function created($model)
    {
        // Increase comment count
        $model->post->comment_count++;
        $model->post->save();
    }

    public function deleting($model)
    {
        // Decrease comment count
        $model->post->comment_count--;
        $model->post->save();
    }
}