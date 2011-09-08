<?php

# checks for HTTP_X_FORWARDED_FOR and HTTP_X_OPERAMINI_PHONE headers
function is_mini_request(){
	return !array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) && !array_key_exists('HTTP_X_OPERAMINI_PHONE',$_SERVER);
}

# clean up some of the $_POST data
function sanitize_post(){

	$filter['url']  = FILTER_SANITIZE_URL;
	$filter['host'] = array('filter'=> FILTER_SANITIZE_STRING,
							'flags' => FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$filter['html'] = FILTER_SANITIZE_SPECIAL_CHARS;

	$sanitized = filter_input_array( INPUT_POST, $filter );

	return $sanitized;
}

# do we have our credentials?
function has_access_key(){
	return defined('AWSACCESSKEY') && !is_null( AWSACCESSKEY );
}

function has_secret_key(){
	return defined('AWSSECRETKEY') && !is_null( AWSSECRETKEY );
}

function has_bucket(){
	return defined('S3BUCKET') && !is_null( S3BUCKET );
}
