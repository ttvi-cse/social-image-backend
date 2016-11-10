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
use User;

class UserController extends APIController
{
    public function register()
    {
        $user = new User();

        $user->fill(\Input::all());

        if ($user->save()) {
            $this->res['data'] = $user->toArray();
        } else {
            $this->res['errors'] = $user->errors();
        }

        return \Response::api($this->res);
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

    public function profile()
    {

    }
}