<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 6:45 PM
 */
class PostRateObserver
{
    public function created($model)
    {
        $model->post->updateRatingAverage($model->grade);
        $model->post->rating_count ++;
        $model->post->save();
    }
}