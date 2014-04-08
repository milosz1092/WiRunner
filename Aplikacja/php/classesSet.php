<?php
	require('php/classes/my_simpleMsg.php');
	require('php/classes/my_getFilename.php');
	require('php/classes/my_validDate.php');
	require('php/classes/my_siteTitle.php');
	require('php/classes/my_mainMenu.php');
	require('php/classes/my_connDb.php');
	require('php/classes/my_eMail.php');
	require('php/classes/my_userAction.php');

	$my_siteTitle = new my_siteTitle;
	$my_mainMenu = new my_mainMenu;
	$my_userAction = new my_userAction;
?>
