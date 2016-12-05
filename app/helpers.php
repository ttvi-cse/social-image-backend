<?php
use Carbon\Carbon;

/**
 * Input Helpers
 */

// get input perpage
function input_perpage(){
	return Input::get('per_page', Config::get('view.per_page'));
}

/**
 * DB Helpers
 */
function db_abscolumn($table, $column){
	return $table . '.' . $column;
}

/**
 * Flash helpers
 */
function flash_created($name, $id){
	return Lang::get('flash_messages.model_created', [
		'm' => $name,
		'i' => $id
	]);
}

function flash_updated($name, $id){
	return Lang::get('flash_messages.model_updated', [
		'm' => $name,
		'i' => $id
	]);
}

function flash_deleted($name, $id){
	return Lang::get('flash_messages.model_deleted', [
		'm' => $name,
		'i' => $id
	]);
}

function flash_cloned($name, $id){
	return Lang::get('flash_messages.model_cloned', [
		'm' => $name,
		'i' => $id
	]);
}

/**
 * Asset helper
 */
function asset_no_image(){
	return asset('assets/images/no-image.png');
}

/**
 * Others
 */
function dt_format($dt){
	if(empty($dt)) {
		return null;
	}

	if(!$dt instanceof Carbon){
		$dt = Carbon::parse($dt);
	}
	return $dt->format(Config::get('api.datetime_format'));
}

function dt_pretty_format($dt, $timeFormat = true){
	if(!$dt instanceof Carbon){
		$dt = Carbon::parse($dt);
	}

	$timeFormatStr = '';
	if($timeFormat) {
		$timeFormatStr =  $dt->format("h:i A");
	}
	
	return $dt->toFormattedDateString() . " " . $timeFormatStr;
}

function size2Byte($size) {
		switch ($size) {
		case ($size / 1073741824) > 1:
			return round(($size/1073741824), 2) . "GB";
		case ($size / 1048576) > 1:
			return round(($size/1048576), 2) . "MB";
		case ($size / 1024) > 1:
			return round(($size/1024), 2) . "KB";
		default:
			return $size . ' Bytes';
	}
}

function isJSON($string){
	 return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

/**
 * Convert any string format to camelCase
 */
function camelCase($str, array $noStrip = []) {
	// non-alpha and non-numeric characters become spaces
	$str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
	$str = trim($str);
	// uppercase the first character of each word
	$str = ucwords($str);
	$str = str_replace(" ", "", $str);
	$str = lcfirst($str);

	return $str;
}

/**
 * PRevent dangerous security issues (XSS, CSRF)
 */
function globalXssClean() {
    // Recursive cleaning for array [] inputs, not just strings.
    // Not apply Xss Clean for special data such as: svg_data
    $sanitized = arrayStripTags(Input::except('svg_data'));
    Input::merge($sanitized);
}

function arrayStripTags($array) {
    $result = array();
    foreach ($array as $key => $value) {
        // Don't allow tags on key either, maybe useful for dynamic forms.
        $key = strip_tags($key);

        // If the value is an array, we will just recurse back into the
        // function to keep stripping the tags out of the array,
        // otherwise we will set the stripped value.
        if (is_array($value)) {
            $result[$key] = arrayStripTags($value);
        } else {
            // I am using strip_tags(), you may use htmlentities(),
            // also I am doing trim() here, you may remove it, if you wish.
            $result[$key] = trim(strip_tags($value));
        }
    }
    return $result;
}

function authUser() {
    return API::user() ? API::user() : Auth::user();
}


/**
 * Generate link to asset with timestamp
 * @param  String $path link to asset CSS or JavaScript
 * @return String 
 */
function assetWithTimestamp($path)
{
    // get caching timestamp
    $cachingDate = Config::get('app.caching_date', '');
    if ($cachingDate) {
       $cachingTimestamp = Carbon::parse($cachingDate)->timestamp;
    } else{
       $cachingTimestamp = Carbon::now()->timestamp;
    }

    return $path . "?" . $cachingTimestamp;
}