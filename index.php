<?php

/* Copyright (c) 2011 Tiffany B. Brown, Opera Software tiffanyb@opera.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Uses Donovan Schonknecht's Amazon S3 Class:
http://undesigned.org.za/2007/10/22/amazon-s3-php-class

*/

# can't do much without these files.
require_once('conf.php');
require_once('funcs.php');
require_once('S3.php');

$segments =  explode( 'index.php', $_SERVER['REQUEST_URI'] );
$last = array_pop( $segments );

( empty($segments) || ( $last == '' || $last == '/') ) ? $controller = 'main' : $controller = trim($last,'/');

load_page($controller);

