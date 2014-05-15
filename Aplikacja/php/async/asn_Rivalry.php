<?php
require('../classes/my_connDb.php');

require('../classes/my_Rivalry.php');
$my_Rivalry = new my_Rivalry;

switch($_POST['type']) {
	case 'rivDel':
		echo $my_Rivalry->delete($_POST['rivId'], $_POST['usrPol']);
	break;
}
?>
