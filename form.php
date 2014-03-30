<?php
use metaviz\UploadFile;

$max = 100 * 1024;
$result = [];
if (isset($_POST['upload'])){
	require_once 'src/metaviz/UploadFile.php';
	$destination = __DIR__ . '/uploaded/';
	try {
		$upload = new UploadFile($destination);
		$upload->setMaxSize($max);
		// $upload->allowAllTypes();
		$upload->upload();
		$result = $upload->getMessages();
	} catch (Exception $e) {
		$result[] = $e->getMessage();
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>MetaViz Upload An Image</title>
	<link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
	<h1>Upload Files</h1>
	<?php 
		if($result){ ?>
		<ul class="result">
			<?php foreach ($result as $message) {
				echo "<li>$message</li>";
			} ?>
		</ul>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<p>
	
	<input type="hidden" name="MAX FILE SIZE" value="<?php echo $max;?>">
	<label for="filename">Select File:</label>
	<input type="file" name="filename" id="filename">
</p>

</form>
	
	
</body>
</html>