<?php

/*
|--------------------------------------------------------------------------
| Register Macros
|--------------------------------------------------------------------------
|
*/

Response::macro('api', function($data, $statusCode = 200)
{
	$responseArray = [
		'message' 			=> NULL,
		'errors' 			=> NULL,
		'data' 				=> NULL,
		'extra_data' 		=> NULL,
	];

	if(isset($data['message'])){
		$responseArray['message'] = $data['message'];
	}

	if(isset($data['errors'])){
		$responseArray['errors'] = $data['errors'];

		if(empty($responseArray['message'])){
			$responseArray['message'] = $data['errors']->first();
		}

	}

	if(isset($data['data'])){
		if(isset($data['data']['data'])){
			$responseArray['data'] = $data['data']['data'];
			$responseArray['extra_data'] = array_except($data['data'], array('data'));	
		} else{
			$responseArray['data'] = $data['data'];
		}
	}

    return Response::make($responseArray, $statusCode);
});


Response::macro('file', function($path){
	// Prepare content headers
	$finfo = finfo_open(FILEINFO_MIME_TYPE); 
	$mime = finfo_file($finfo, $path);
	$length = filesize($path);
 
	$header = array(
        'Content-Disposition' => 'inline; filename="' . File::name($path) . '"',
        'Last-Modified' => File::lastModified($path),
        'Cache-Control' => 'must-revalidate',
        'Content-Type' => $mime,
        'Content-Length' => $length
    );

    return Response::make(File::get($path), 200, $header);
});

Response::macro('apk', function($path){
	header('Content-Description: File Transfer');
	header('Content-Type: application/vnd.android.package-archive');
	header('Content-Disposition: attachment; filename='.basename($path));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($path));
	ob_clean();
	flush();
	readfile($path);
	exit;
});