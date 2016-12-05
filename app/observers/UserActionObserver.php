<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/10/2016
 * Time: 9:21 PM
 */
class UserActionObserver
{
    public function created($model)
    {
//        if(in_array($model->action_id, Notification::$target_actions)){
//            Notification::pushToAndroid($model);
//        }
    }
}