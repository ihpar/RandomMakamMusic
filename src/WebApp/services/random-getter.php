<?php
	// get random numbers from DB
	// this page is included in song-generator.php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "turca";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$notes = array();
	$durations = array();
	$usedNums = array();
	
	$sql = "SELECT * FROM numbers " . 
	"WHERE used=0 " . 
	"ORDER BY id " . 
	"LIMIT 256";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		$index = 0;
		while($row = $result->fetch_assoc()) {
			if($index < 128) {
				array_push($notes, $row["number"]);
			}
			else {
				array_push($durations, $row["number"]);
			}
			$index++;
			
			array_push($usedNums, $row["id"]);
		}
	} 
	else {
		echo "0 results";
	}

	$sql = "UPDATE numbers SET used=1 " . 
		"WHERE id in(" . implode(",", $usedNums) . ")";
	$result = $conn->query($sql);

	/*
		echo "<br>------<br>";
		print_r($notes);
		echo "<br>------<br>";
		echo "C:" . count($notes);
		echo "<br>------<br>";
		print_r($durations);
		echo "<br>------<br>";
		echo "C:" . count($durations);
		echo "<br>------<br>";
	*/
	// temp random numbers
	// $notes     = array(15,17,18,22,22,7,8,9,11,6,14,6,9,30,16,11,3,31,4,7,15,26,20,8,12,23,11,29,13,21,26,23,28,20,20,31,29,20,0,4,6,20,20,1,29,4,1,30,3,7,12,22,13,26,23,20,4,29,3,21,28,3,21,21,12,25,18,4,25,10,8,17,17,2,25,16,24,19,2,18,20,11,10,14,1,23,11,21,14,13,26,11,13,29,3,5,22,29,10,12,5,25,4,9,19,1,6,7,11,21,21,11,31,23,15,19,12,17,20,14,16,18,18,19,16,28,17,13);
	// $durations = array(16,14,13,26,15,19,12,20,11,3,29,20,0,22,17,11,13,4,18,4,25,10,8,17,17,2,25,7,11,21,11,31,24,11,21,23,20,14,19,2,18,20,11,10,14,1,23,16,18,18,19,22,15,26,20,6,9,16,28,17,13,3,5,22,29,10,12,5,25,21,26,23,28,20,8,9,11,6,29,4,1,30,3,7,12,7,14,4,7,15,17,18,30,16,29,13,29,4,6,20,20,1,31,8,12,23,11,31,22,13,26,23,20,4,29,3,21,28,9,19,1,6,3,21,21,12,25,21);
	$randomResult = array("notes" => $notes, "durations" => $durations);
?>