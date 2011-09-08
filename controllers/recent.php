<?php

/* Copyright (c) 2011 Tiffany B. Brown, Opera Software tiffanyb@opera.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

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

require_once('S3.php');

$base_url = sprintf('http://%s.s3.amazonaws.com/',
						S3BUCKET);
$S3 = new S3(AWSACCESSKEY, AWSSECRETKEY);

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

<h1>Recently saved source code files</h1>

<ul>
<?

if( ( $contents = $S3->getBucket( S3BUCKET ) ) !== false){

	foreach($contents as $key=>$value){
	 	$link = sprintf('<li><a href="%s">%s</a> (saved: %s)</li>',
	 					$base_url.$value['name'],
	 					$value['name'],
	 					date('d M Y',$value['time']) );
	 	echo $link;


	}
}
?>
</ul>

</body>
</html>
