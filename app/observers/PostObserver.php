<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 6:25 PM
 */
class PostObserver
{
    public function deleting($model)
    {
        $model->comments()->delete();

//        $model->created_by_user
//            ->activites()
//            ->where('target_id', $model->id)
//            ->whereIn('target_type_id', [1,2,3])
//            ->delete();
    }

    public function created($model)
    {
//         Attach created action

//        $model->created_by_user->attachAction(
//            10, // Created
//            $model->title,
//            $model->id
//        );
    }
}