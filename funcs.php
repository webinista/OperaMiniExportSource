<?php

# define some language constants
define('FILE_CREATED_TITLE','File created');
define('FILE_CREATED_BODY','View it at the following URL.');

define('FILE_CREATION_FAILED_TITLE',"Oh dear. That didn't work.");
define('FILE_CREATION_FAILED_BODY',"Seems I couldn't create that file for you. Try checking your AWS credentials in conf.php");

define('NO_HTML_TITLE',"Can't create a file");
define('NO_HTML_BODY',"There was no HTML to save.");

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

function save_to_s3($s3obj, $html, $bucket, $filename, $publicread = 'public-read'){
	if( !class_exists('S3') ):
		throw new Exception("Wait! The S3 Class has not been defined.");
	else:
		# add a comment for the original URL name and date
		$comments = sprintf('<!-- from: %s on: %s -->',
						$santized['url'],
						date('r') );

		$success = $s3obj->putObject( $html.$comments,
					   $bucket,
					   $filename,
					   $publicread, // make it readable or not
					   array(),
					   array("Content-Type" => "text/html") );
		return $success;
	endif;
}

function load( $template, $dataArray ){
	extract( $dataArray, EXTR_OVERWRITE );
	include( $template );
}
