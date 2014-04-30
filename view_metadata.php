<?php 
	
	session_start();
	$name = "";
	if (isset($_POST['submit']) && isset($_SESSION['user_hashes'])) {
		$name=$_POST['name'][0];
	} else{
		header("Location:404.php");
		exit;
	}
	function make_thumb($src, $dest, $desired_width) {
		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>View Metadata</title>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>
<br>
<?php
	#print_r("$_SESSION['user_hashes'][$name][1]");
	$image = $_SESSION['user_hashes'][$name];
	$i=0;
	#print_r($image);
	foreach ($image as $keypair) {
		if ($i==0) {
			$i++;
			continue;
		}
		if ($i==1) {
			echo "<h1>$keypair[1]</h1>";
			$src= "uploaded/".$keypair[1];
			$dst = "uploaded/thumbnail.jpg";
			$width = 600;
			make_thumb($src,$dst,$width);
			echo "<img src=uploaded/thumbnail.jpg alt=$keypair[1]>";
		}
		else{
			echo "$keypair[0] : $keypair[1]";
			echo "<br />";
		}
		$i++;
	}
	#print_r($_SESSION['user_hashes'][0]);
 ?>
</body>
</html>