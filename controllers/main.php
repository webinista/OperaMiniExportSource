<?php

/* Copyright (c) 2011 Tiffany B. Brown, Opera Software tiffanyb@opera.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Uses Donovan Schonknecht's Amazon S3 Class:
http://undesigned.org.za/2007/10/22/amazon-s3-php-class

*/

# die if we don't have an access key
if( !has_access_key() ):
	die('Please define an Amazon Web Services Access Key');
endif;

# die if we don't have a secret key
if( !has_secret_key() ):
	die('Please define your Amazon Web Services Secret Key');
endif;

# die if we don't have a bucket
if( !has_bucket() ):
	die('Please define your S3 bucket');
endif;

# die if this doesn't look like an Opera Mini request
# ( easy to spoof these headers though )

if( is_mini_request() ):
	header("HTTP/1.1 400 Bad Request");
	$data['title'] = 'HTTP/1.1 400 Bad Request';
	load_template('tpl/badrequest.php', $data);

	die;
else:

	/*-----------------
	Start the magic
	-----------------*/

	# clean up the host and URL a bit
	$sanitized = sanitize_post();

	# if we have some HTML to save
	if( $_POST['html'] !== ''):

		# reformat and generate file name
		$filename = sprintf('%s_%d.html', preg_replace('/\W/','', $sanitized['host']), time() );

		# create a new S3 object
		$S3 = new S3(AWSACCESSKEY, AWSSECRETKEY);

		# save it to S3
		$success =  save_to_s3( $S3, $_POST['html'], S3BUCKET, $filename, S3::ACL_PUBLIC_READ );

		# if that worked ...
		if( $success ):
			$data['title']    = FILE_CREATED_TITLE;
			$data['content']  = FILE_CREATED_BODY;
			$data['view_url'] = sprintf('http://%s.s3.amazonaws.com/%s', S3BUCKET, $filename );
		else:
			# View URL
			$data['title']    = FILE_CREATION_FAILED_TITLE;
			$data['content']  = FILE_CREATION_FAILED_BODY;
			$data['view_url'] = '';
		endif; // end success loop.

	else:
		# if we don't have some HTML.
		$data['title']    = NO_HTML_TITLE;
		$data['content']  = NO_HTML_BODY;
		$data['view_url'] = '';

	endif;

	load_template('tpl/created.php', $data);

endif;

