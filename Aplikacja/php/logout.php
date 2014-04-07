<?php
	session_start();
	
	$_SESSION['WiRunner_log_id'] = 0;
	$_SESSION['WiRunner_policy'] = 0;
	$_SESSION['WiRunner_login'] = '';

	header("Location: ../login.php");
?>
