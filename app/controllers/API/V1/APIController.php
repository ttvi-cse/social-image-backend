<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/20/2016
 * Time: 4:22 PM
 */
use \Dingo\Api\Routing\ControllerTrait;

class APIController extends \Controller
{
    use ControllerTrait;

    protected $res = ['message', 'errors', 'data'];
}