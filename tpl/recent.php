<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=yes">
	<title>Export Opera Mini Source</title>
	<link rel="stylesheet" href="../tpl/s.css">
</head>
<body>

<?php if( !empty($contents) ): ?>
<h1>Viewing recently exported HTML files</h1>
<ul>
<?php foreach($contents as $k=>$v): ?>

<li><a href="<?php echo $baseurl.$k; ?>"><?php echo $k; ?></a></li>

<?php endforeach; ?>
</ul>
<?php else: ?>

<h1>Error!</h1>
<p>No files available.</p>

<?php endif; ?>


</body>
</html>