<?php

	// kaunsa : json field usko extract karke case
	$request = json_decode(	file_get_contents("php://input"));
	$num1 = $request->result->parameters->num1;
	$num2 = $request->result->parameters->num2;
	$speech = "Sum of ".$num1." and ".$num2." is ".($num1+$num2);
	$response = array(
		"speech" => $speech,
		"displayText" => $speech
	);

	header('Content-Type: application/json');
	echo json_encode($response);
	
	/*
	// DATABSE
	$dhost=":/cloudsql/myapp-1ab28:poshdb";
	$duser="root";
	$dpassword="";
	$database="poshdb";
	$speech = "NULL";

	$connection=mysql_connect($dhost, $duser, $dpassword) or die("Could not Connect to SQL Server Suleman");
	
	$db=mysql_select_db($database, $connection) or die(" Check the Database Name from Config.php , wrong database entered ");


	if($connection == null) {
		$speech = "NULL";		
	} else {
		$speech = "Connected to database";
	}

	$response = array(
		"speech" => $speech,
		"displayText" => $speech
	);

	header('Content-Type: application/json');
	echo json_encode($response);
	*/
?>