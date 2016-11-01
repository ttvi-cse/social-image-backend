<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
//require app_path() . '/routes/model_binding.php';


Route::get('/', function()
{
	return View::make('hello');
});

/**
 * API V1
 */
require app_path() . '/routes/apiv1.php';

/**
 * Portal
 */
//require app_path() . '/routes/portal.php';

