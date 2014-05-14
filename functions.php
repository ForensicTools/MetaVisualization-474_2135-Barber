<?php 

function connect_to_db(){
	 return new mysqli("localhost","root","yourpassword", "MetaViz");
	 #return $conn;
}

function check_new_metadata($conn){
	if (isset($_SESSION['hashes'])) {
		if (!isset($_SESSION['user_hashes'])) {
			$_SESSION['user_hashes'] = [];
		}
		$hashval = str_replace(array("[","]",","), "", $_SESSION['hashes']);
		$hashlist = str_split($hashval, 67);
		foreach ($hashlist as $hash) {
			$current_hashes = get_hashes();
			if (!in_array(str_replace("'", "",$hash), $current_hashes)) {
				if ($values = $conn->query("SELECT * FROM images WHERE Hash = $hash")) {
					$image_metadata = [];
					while ($row = $values->fetch_assoc()) {
						foreach ($row as $key => $value) {
							if (isset($value)) {
								$keypair = ["$key","$value"];
								$image_metadata[] = $keypair;
							}
						}
					}
				}
				array_push($_SESSION['user_hashes'],$image_metadata);
			}
		}
		unset($_SESSION['hashes']);
	} else {
		if (!isset($_SESSION['user_hashes'])) {
			header("Location: form.php");
			exit;
		}
	}

	for ($i=0; $i < 5; $i++) { 
		if (!empty($_POST["pick$i"])) {
			$_SESSION["picked$i"] = $_POST["pick$i"];
			unset($_POST["pick$i"]);
		}
	}
}
function get_metadata(){
	foreach ($_SESSION['user_hashes'] as $image) {
		foreach ($image as $keypair) {
			echo "$keypair[0] : $keypair[1]";
			echo "<br />";
		}
	}
}

function get_graph_options(){
	$alloptions = [];
	foreach ($_SESSION['user_hashes'] as $image) {
		foreach ($image as $keypair) {
			if (is_numeric($keypair[1]) && !in_array($keypair[0],$alloptions)){
				$alloptions[] = $keypair[0];
			}	
		}
 	}
 	return $alloptions;
}

function get_hashes(){
	$currnt_hashes = [];
	foreach ($_SESSION['user_hashes'] as $image) {
		$hashy = $image[0][1];
		$currnt_hashes[] = $hashy;
	}
	return $currnt_hashes;
}
function get_names(){
	$names = [];
	foreach ($_SESSION['user_hashes'] as $image) {
		$names[] = $image[1][1];
	}
	return $names;
}

function list_images(){
	$images = get_names();
	echo "<br />";
	echo "<h1><p>Images</p></h1>";
	$i=0;
	foreach ($images as $name) {
		echo "<p><h3>$name</h3></p>";
		echo "<form action=view_metadata.php method=POST target=\"_blank\"><input type=submit name=submit value=\"View Full Metadata\"><input type=hidden name=name value=$i/></form>";
		$i++;
	}
}

function get_graph_values(){
	$fullset = [];
	foreach ($_SESSION['user_hashes'] as $image) {
		$dataset = [];
		for ($i=0; $i < 5; $i++) { 	
			if (!empty($_SESSION["picked$i"])) {
				foreach ($image as $keypair) {
					if ($keypair[0]==$_SESSION["picked$i"]) {
						$data = $keypair[1];
					}
				}
				if (empty($data)) {
					$data = 0;
				}
				$dataset[] = $data;
			}
		}
		$fullset[] = $dataset;
	}
	return $fullset;
}


function simple(){
	echo "The current hashes are <br>";
	$asdf = get_hashes();
	foreach ($asdf as $key) {
		echo "$key";	
		echo "<br>";	
	}
}

function generate_graph(){
	 include("src/pChart127/pChart/pData.class");
	 include("src/pChart127/pChart/pChart.class");


	 // Dataset definition 
	 $DataSet = new pData;
	 
	 if (empty($_SESSION["picked0"])) {
	 	$DataSet->AddPoint(array(0,0),"");
	 	$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie();
		$title = "Please select an value to compare by.";
	 }
	 else{
	 	 $title = "";
	 	 for ($i=0; $i < 5; $i++) { 
	 	 	if (!empty($_SESSION["picked$i"])) {
	 	 		if ($title!="") {
				$title.=" vs. ";
				}
				$title.=$_SESSION["picked$i"];
			}
	 	 }
	 	 $listofnames=get_names();
	 	 $dataset = get_graph_values();
		 $i = 0;
		 foreach ($listofnames as $name) { 	
		 	$DataSet->AddPoint($dataset[$i],"Serie$i");
		 	$i++;
		 }
		 $DataSet->AddAllSeries();
		 $DataSet->SetAbsciseLabelSerie();
		 $i = 0;
		 foreach ($listofnames as $name) {
		 	$DataSet->SetSerieName("$name","Serie$i");
		 	$i++;
		 }
	 }
	 echo "<h1><p>Graph</p></h1>";
	 // Initialise the graph
	 $Test = new pChart(700,230);
	 $Test->setFontProperties("src/pChart127/Fonts/tahoma.ttf",8);
	 $Test->setGraphArea(50,30,585,200);
	 $Test->drawFilledRoundedRectangle(7,7,693,223,5,132,153,172);
	 $Test->drawRoundedRectangle(5,5,695,225,5,80,80,80);
	 $Test->drawGraphArea(132,153,172,TRUE);
	 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,215,225,235,TRUE,0,2);
	 $Test->drawGrid(4,TRUE,50,50,50,50);
	 // Draw the 0 line
	 $Test->setFontProperties("src/pChart127/Fonts/tahoma.ttf",6);
	 $Test->drawTreshold(0,250,255,252,TRUE,TRUE);

	 // Draw the cubic curve graph
	 $Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,50);

	 // Finish the graph
	 $Test->setFontProperties("src/pChart127/Fonts/tahoma.ttf",8);
	 $Test->drawLegend(600,30,$DataSet->GetDataDescription(),215,225,235);
	 $Test->setFontProperties("src/pChart127/Fonts/tahoma.ttf",10);
	 $Test->drawTitle(50,22,"$title",250,250,250,585);
	 $Test->Render("src/pChart127/pChart/hashgraph1.png");
}
function make_options_button($count=1){
	$total_list = get_graph_options();
	for ($i=0; $i <$count ; $i++) { 		
		echo "<select name=\"pick$i\">";
		foreach ($total_list as $option) {
			echo "<option";
			if(isset($_SESSION["picked$i"]) && $option==$_SESSION["picked$i"]) {
				echo " selected";
			}
			echo " value=\"$option\">$option</option>";
		}
		echo "</select>";
	}

	
} 

?>
