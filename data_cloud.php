<?php
	// DATABSE
	$dhost=":/cloudsql/myapp-1ab28:poshdb";
	//myapp-1ab28:us-central1:posh

	$duser="root";
	$dpassword="";
	$database="poshdb";
	$speech = "NULL";

	echo "Step 1";
	$connection=mysql_connect($dhost, $duser, $dpassword) or die("Could not Connect to SQL Server Suleman");
	echo "Step 2";
	
	$db=mysql_select_db($database, $connection) or die(" Check the Database Name from Config.php , wrong database entered ");
	echo "Step 3";


	if($connection == null) {
		$speech = "NULL";		
	} else {
		$speech = "Connected to database";
	}

	$response = array(
		"speech" => $speech,
		"displayText" => $speech
	);

	echo "Step 4";

	header('Content-Type: application/json');
	echo json_encode($response);
	echo "Step 5";

	?>