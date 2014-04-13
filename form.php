<?php
use metaviz\UploadFile;

session_start();
require_once 'src/metaviz/UploadFile.php';
if (!isset($_SESSION['maxfiles'])) {
	$_SESSION['maxfiles'] = ini_get('max_file_uploads');
	$_SESSION['postmax'] = UploadFile::convertToBytes(ini_get('post_max_size'));
	$_SESSION['displaymax'] = UploadFile::convertFromBytes($_SESSION['postmax']);
}

$max = 1000 * 1024;
$result = array();

if (isset($_POST['upload'])){
	$destination = __DIR__ . '/uploaded/';
	try {
		$upload = new UploadFile($destination);
		$upload->setMaxSize($max);
		$upload->allowAllTypes();
		$upload->upload();
		$result = $upload->getMessages();
	} catch (Exception $e) {
		$result[] = $e->getMessage();
	}
}
$error = error_get_last();
?>


<!doctype html>
<html lang="en">
<head> 
	<meta charset="UTF-8">
	<title>MetaViz Upload An Image</title>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>
<body>
	<div class="body">
	<h1>Upload Files</h1>
	<?php if($result || $error){ ?>
		<ul class="result">
			<?php 

			if ($error) {
				echo "<li>{$error['message']}</li>";
			}
			if ($result) {
				foreach ($result as $message) {
					echo "<li>$message</li>";
				}
			} ?>
		</ul>
	<?php } ?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<p>
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max;?>">
<label for="filename">Select File:</label>
<input type="file" name="filename[]" id="filename" multiple
data-maxfiles="<?php echo $_SESSION['maxfiles'];?>"
data-postmax="<?php echo $_SESSION['postmax'];?>"
data-displaymax="<?php echo $_SESSION['displaymax'];?>">
</p>
<ul>
    <li>Up to <?php echo $_SESSION['maxfiles'];?> files can be uploaded simultaneously.</li>
    <li>Each file should be no more than <?php echo UploadFile::convertFromBytes($max);?>.</li>
    <li>Combined total should not exceed <?php echo $_SESSION ['displaymax'];?>.</li>
</ul>
<p>
<input type="submit" name="upload" value="Upload File">
</p>
</form>
</div>
<script src="js/checkmultiple.js"></script>
</body>
</html>