<?php
	ini_set('max_execution_time', 3000); //3000 seconds = 50 minutes
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
	
	$numbersFile = fopen("data.txt", "r") or die("Unable to open file!");
	$i = 0;
	while ($line = fgets($numbersFile)) {
		$sql = "INSERT INTO numbers (id, number, used)
		VALUES (NULL, $line, 0)";
		
		if ($conn->query($sql) === TRUE) {
			
		} 
		else {
			echo "Error: " . $sql . "<br>" . $conn->error;
			break;
		}
	}
	fclose($numbersFile);
	$conn->close();
?>