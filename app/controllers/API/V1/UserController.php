<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/20/2016
 * Time: 4:23 PM
 */

namespace API\V1;

use APIController;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends APIController
{
    public function register()
    {

    }

    public function login()
    {
        $credentials = \Input::only('username', 'password');

        try {
            if (! $token = \JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized('Invalid credentials');
            }
        } catch (JWTException $e) {
            return $this->response->errorInternal('Could not create token.');
        }

        // Get user
        $user = \JWTAuth::setToken($token)->toUser();
        
        $resUser = $user->toArray();
        $resUser['token'] = $token;
        $resUser['expiry_in'] = \Config::get('jwt::ttl');

        $reflector = new \ReflectionClass('\Response');
        echo $reflector->getFileName();

        return \Response::api(['data' => $resUser]);
    }

    public function logout()
    {
        Auth::logout();
        return \Response::api(array(
            'message' => null,
            'errors' => null,
            'data' => array()
        ));
    }

    public function pprofile()
    {

    }
}