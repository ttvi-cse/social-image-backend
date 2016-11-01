<?php

// ------------------------------------------------------------
// Error Handlers
// ------------------------------------------------------------

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

// General HttpException handler
App::error(function(Symfony\Component\HttpKernel\Exception\HttpException $e, $code)
{
	$headers = $e->getHeaders();

	switch ($code)
	{
		case 401:
			$default_message = 'Invalid API key';
			$headers['WWW-Authenticate'] = 'Basic realm="REST API"';
		break;

		case 403:
			$default_message = 'Insufficient privileges to perform this action';
		break;

		case 404:
			$default_message = 'The requested resource was not found';
		break;

		case 418:
			$default_message = 'The requested resource only availble for mobile app';
		break;

		default:
			$default_message = 'An error was encountered';
	}

	if (Request::is('api/*'))
	{
		return Response::json(array(
			'error' => $e->getMessage() ?: $default_message,
		), $code, $headers);
	} else{
		return View::make("portal.errors." . $code);
	}
});