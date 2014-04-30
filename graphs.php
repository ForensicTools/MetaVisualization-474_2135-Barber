<?php 

session_start();
require_once("functions.php");
$conn = connect_to_db();
check_new_metadata($conn);
list_images();
?>

<!DOCTYPE html>
<html>
<head>
	<title>MetaViz - Image Comparison</title>
	<link rel="stylesheet" type="text/css" href="css/graph.css">
</head>
<body>
	<?php 
		echo "<br />";
		#simple();
		generate_graph();
	?>
	<img src="src/pChart127/pChart/hashgraph1.png" alt="">
	<?php
		echo "<form action=\"\" method=POST>";
		make_options_button(5); 
		echo "<input type=\"submit\" value=\"Update Graph\"></form>";
	?>
	<br>
	<form action="form.php" method="GET"><input type="submit" value="Upload More Images"></form>
	<form action="form.php" method="POST"><input type="submit" name="reset" value="Reset"></form>

</body>
</html>