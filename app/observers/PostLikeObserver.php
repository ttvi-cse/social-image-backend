<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/10/2016
 * Time: 9:52 PM
 */
class PostLikeObserver
{
    public function created($model)
    {
        $model->post->like_count ++;
        $model->post->save();
    }
}