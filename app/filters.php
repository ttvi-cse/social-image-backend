<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	globalXssClean();
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('admin/login');
		}
	}
});

Route::filter('auth.basic', function($route, $request)
{
	$authInfo = $request->header('Basic-Authorization');

		if(!$authInfo || $authInfo != 'Basic ' . base64_encode(Config::get('api.basic_auth_secret_key'))) {

			$response = Response::make([
				'message' => 'Invalid basic authentication credentials.'
		], 401);
		return $response;
	}
});

Route::filter('auth.jwt', function()
{
	try {

			if (! $user = JWTAuth::parseToken()->toUser()) {
				return Response::make([
					'message' => 'User not found'
				], 404);
			}

			if($user->status_id != 1) {
				return Response::make([
					'message' => 'Your account is inactive!'
				], 401);
			}

		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

				return Response::make([
					'message' => 'Token expired'
			], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

			return Response::make([
					'message' => 'Token invalid'
			], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

			return Response::make([
					'message' => 'Token absent'
			], $e->getStatusCode());

		}
});

Route::filter('api.perm', function($route, $request){
	$user = API::user();

	if($user){
		if($user->status_id != 1) {
			return Response::make([
				'message' => 'Your account is inactive!'
			], 401);
		}

		$perms = Config::get('permission.api', []);
		$routeName = $route->getName();
		$allowedUserTypes = $perms[$routeName];

		if(!in_array($user->type_id, $allowedUserTypes)){
			return Response::make([
				'message' => 'Permission dennied'
			], 404);
		}
	}
});

Route::filter('article.permView', function($route, $request){
	$article = $route->getParameter('articles');
	$user = \API::user();

	if($user && !$user->isAdmin){
		$canView = false;
		$articleClassIds = $article->classes()->lists('id');
		foreach ($user->classes as $class) {
			if(in_array($class->id, $articleClassIds)) {
				$canView = true;
				break;
			}
		}
		
		if(!$canView){
			App::abort(404);
		}
	}
});

Route::filter('portal.perm', function($route, $request){
	$user = Auth::user();
	if($user){
		$routeName = $route->getName();

		if(!$user->canAccessPortalRoute($routeName)){
			App::abort(404);
		}

		require app_path() . '/routes/portal_menu.php';
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
