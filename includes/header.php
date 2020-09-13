<?php 
	require_once $_SERVER['DOCUMENT_ROOT'] . '/GLOBALS.php';
	
	session_start();

	// if OTP was generated, and page was refreshed
	// clear the session
	if(isset($_SESSION['OTP'])) {
		session_unset();
		session_start();
	}
	
	$_SESSION['fingerprint'] = hash('sha256', $_SERVER['HTTP_USER_AGENT'].$_SERVER['IP']);
	$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];

	if(empty($_SESSION['first_access']))
		$_SESSION['first_access'] = time();
?>

<!doctype html>
<!--[if lt IE 7]>   <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="">   <![endif]-->
<!--[if IE 7]>      <html class="no-js lt-ie9 lt-ie8" lang="">          <![endif]-->
<!--[if IE 8]>      <html class="no-js lt-ie9" lang="">                 <![endif]-->
<!--[if gt IE 8]><!-->
<html> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible">
	<title></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">

	<!-- Open Sans Font -->
	<link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,600,600italic' type='text/css'>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<!-- Font Awesome CSS -->
	<link type="text/css" rel="stylesheet" href="/css/font-awesome.min.css" >
	<!-- Animate CSS-->
	<link rel="stylesheet" href="/css/animate.min.css">
	<!-- Custom Styling-->
	<link rel="stylesheet" href="/css/basic.css">
	<link rel="stylesheet" href="/css/main.css">
	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="/js/main.js"></script>

	<script>
		$(document).ready(function() {
			setTimeout(function(){
				$('#hero').removeClass('hidden').addClass('animated fadeIn');
				$('.footer').removeClass('hidden').addClass('animated fadeIn');
				$('#spinner').remove();
			}, 1500);
		});
	</script>

	</head>
	<body>
		<!--[if lt IE 8]>
			<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience.</p>
		<![endif]-->
