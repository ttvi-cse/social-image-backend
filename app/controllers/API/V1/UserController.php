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

    public function show($user)
    {

        $this->res['data'] = $user;

        return \Response::api($this->res);
    }

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

    /**
     * User's avatar
     */
    public function avatar($user)
    {
        $method = \Request::method();

        switch ($method) {
            case 'GET':
                $imagePath = $user->avatar->url('small');
                break;
            case 'POST':
                if($user->id != \API::user()->id){
                    \App::abort('404');
                }

                if(!\Input::hasFile('avatar')){
                    $user->addError('avatar', 'Can not get input file');

                    $this->res['errors'] = $user->errors();
                    return \Response::api($this->res);
                } else{
                    $user->avatar = \Input::file('avatar');
                    $user->save();

                    $this->res['message'] = "Avatar has been updated";
                    return \Response::api($this->res);
                }

                break;
            default:
                # code...
                break;
        }

        $imagePath = $user->avatar->path('small');
        \Log::info($imagePath);
        return \Response::file($imagePath);
    }

    /**
     * Update user's action
     *
     * @return Response
     */
    public function actions()
    {
        // return \API::user()->my_events()->get();
        $actionId = \Input::get('action_id', '');
        $actionValue = \Input::get('action_value', '');
        $targetId = \Input::get('target_id', '');
        $targetTypeId = \Input::get('target_type_id', '');

        $result = \API::user()->attachAction($actionId, $actionValue, $targetId, $targetTypeId);

        if($result){
            $this->res['message'] = \Lang::get('flash_messages.user_action_success');
        } else{
            $this->res['errors'] = \API::user()->errors();
        }

        return \Response::api($this->res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($user)
    {
        if($user->id != \API::user()->id){
            \App::abort('404');
        }

        $user->fill(\Input::all());

        if($user->save()){
            $this->res['data'] = $user->toArray();
        } else{
            $this->res['errors'] = $user->errors();
        }

        return \Response::api($this->res);
    }


}