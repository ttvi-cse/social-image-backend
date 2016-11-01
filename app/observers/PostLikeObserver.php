<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 6:48 PM
 */
class PostLikeObserver
{
    public function created($model)
    {
        $model->post->like_count ++;
        $model->post->save();
    }

}