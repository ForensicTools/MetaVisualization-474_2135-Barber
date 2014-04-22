<?php 

session_start();
#require_once("src/metaviz/functions.php");
#connect_to_db();
$conn = new mysqli("localhost","root","yourpassword", "MetaViz");
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>MetaViz - Image Comparison</title>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>
<body>
	<?php if (isset($_SESSION['hashes'])) {
		$hashval = str_replace(array("[","]"), "", $_SESSION['hashes']);
		#Seperate if multiple hashes
		if ($values = $conn->query("SELECT * FROM images WHERE hash = $hashval")) {
			while ($row = $values->fetch_assoc()) {
				foreach ($row as $key => $value) {
					if (isset($value)) {
						echo "$key = $value<br>";	
					}
				}
				}
			#mysqli_free_result($values);
		}

	}
	?>

</body>
</html>