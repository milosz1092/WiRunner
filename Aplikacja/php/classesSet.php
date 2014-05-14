<?php
	require('php/classes/my_simpleMsg.php');
	require('php/classes/my_getFilename.php');
	require('php/classes/my_validDate.php');
	
	require('php/classes/my_siteTitle.php');
	$my_siteTitle = new my_siteTitle;
	
	require('php/classes/my_mainMenu.php');
	$my_mainMenu = new my_mainMenu;
	
	require('php/classes/my_connDb.php');
	
	require('php/classes/my_simpleDbCheck.php');
	$my_simpleDbCheck = new my_simpleDbCheck;

	require('php/classes/my_Poster.php');
	$my_Poster = new my_Poster;
	
	require('php/classes/my_eMail.php');
	
	require('php/classes/my_userAction.php');
	$my_userAction = new my_userAction;
?>
