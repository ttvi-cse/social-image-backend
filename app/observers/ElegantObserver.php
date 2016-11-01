<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 9:08 AM
 */
class ElegantObserver
{
    public function saving($model){
        $rules = $model->getRules();
        $isCreating = isset($model->id) ? false : true;
        if(!$isCreating){
            $rules = $model->getUpdateRules();
        }

        // Validate
        if(!$model->isValid($rules)){
            return false;
        }

        // Update created_at, updated_at
        $user = API::user() ? API::user() : Auth::user();
        if($user){
            $model->updated_by = $user->id;

            if($isCreating){
                $model->created_by = $user->id;
            }
        }
    }
}