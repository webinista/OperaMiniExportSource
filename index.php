<?php

/* Copyright (c) 2011 Tiffany B. Brown, Opera Software tiffanyb@opera.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/


require_once('conf.php');

# die if we don't have an access key
if( !defined('AWSACCESSKEY') || is_null( AWSACCESSKEY ) ):
	die('Please define an Amazon Web Services Access Key');
endif;

# die if we don't have a secret key
if( !defined('AWSSECRETKEY') || is_null( AWSSECRETKEY ) ):
	die('Please define your Amazon Web Services Secret Key');
endif;

# die if we don't have a bucket
if ( !defined('S3BUCKET') || is_null( S3BUCKET ) ):
	die('Please define your S3 bucket');
endif;

# uses the Amazon S3 Class: http://undesigned.org.za/2007/10/22/amazon-s3-php-class
require_once('S3.php');

# if this request doesn't contain the HTTP_X_FORWARDED_FOR or HTTP_X_OPERAMINI_PHONE headers
if(
	!array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) &&
	!array_key_exists('HTTP_X_OPERAMINI_PHONE',$_SERVER)
):
	header("HTTP/1.1 400 Bad Request");
	die;
else:
	# filter the URLs
	$filter['url']  = FILTER_SANITIZE_URL;
	$filter['host'] = array('filter'=> FILTER_SANITIZE_STRING,
							'flags' => FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$filter['html'] = FILTER_SANITIZE_SPECIAL_CHARS;

	$sanitized = filter_input_array( INPUT_POST, $filter );

	# clean up the file name
	$filename = preg_replace('/\W/','', $_POST['host'].'_'.time() ).'.html';

	# create a new S3 object
	$S3 = new S3(AWSACCESSKEY, AWSSECRETKEY);

	$comments = '<!-- from: '.$sanitized['url'].' on: '.date('r').'-->';

	# use the raw HTML as sent and append the comment string.
	$html = $_POST['html'] . $comments;

	# save it to S3
	$success = $S3->putObject($html,
					   S3BUCKET,
					   $filename,
					   S3::ACL_PUBLIC_READ,
					   array(),
					   array("Content-Type" => "text/html") );

	# generate the view URL
	$view_url = sprintf('http://%s.s3.amazonaws.com/%s',
						S3BUCKET,
						$filename );
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=yes">
	<title>Opera Mini Source</title>
	<style media="screen">
		body{
			font: 13px / 1.5 helvetica, arial, sans-serif
		}

	</style>
</head>
<body>
<?
# did it work?
if($success): ?>
<h1>You can view the file here at the following URL</h1>
<p><a href="<?=$view_url;?>"><?=$view_url;?></a></p>
<p><a href="recent/">View recently saved pages</a></p>
<? else:
# no it didn't.
?>
<h1>Couldn't save that source code!</h1>
<p>Not sure what went wrong. You should probably check that you have the right credentials.</p>
<? endif; ?>

</body>
</html>

<? endif; ?>