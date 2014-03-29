<?php

$message = '';
if (isset($_POST['upload'])){
	switch ($_FILES['filename']['error']) {
		case 0:
			$message = $_FILES['filename']['name'] . ' was uploaded successfully.';
			break
		case 2:
			$message = $_FILES['filename']['name'] . ' is too big.';
			break
		case 4:
			$message = 'No file selected.'

		default:
			$message = 'Sorry, there was a problem uploading '. $_FILES['filename']
			break;
			
	}
}


<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>MetaViz Upload An Image</title>
	<link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
	<h1>Upload Files</h1>
	
	
</body>
</html>