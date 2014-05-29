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

	require('php/classes/my_Rivalry.php');
	$my_Rivalry = new my_Rivalry;
	
	require('php/classes/my_eMail.php');
	
	require('php/classes/my_userAction.php');
	$my_userAction = new my_userAction;

	require('php/classes/my_activities.php');
	$my_activities = new my_activities;

	require('php/classes/my_usersRelations.php');
	$my_usersRelations = new my_usersRelations;

	require('php/classes/my_comments.php');
	$my_comments = new my_comments;
?>
