<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/MP_GLOBALS.php';
	//require_once $_SERVER['DOCUMENT_ROOT'] . '/MajorProject' . '/MP_GLOBALS.php';

$query = $_GET['q'];

$my_file = 'QueryString.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
fwrite($handle, $query);
fclose($handle);

?>
