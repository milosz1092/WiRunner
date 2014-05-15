<?php
require('../classes/my_connDb.php');

require('../classes/my_Poster.php');
$my_Poster = new my_Poster;

switch($_POST['type']) {
	case 'msgDel':
		echo $my_Poster->delMsg($_POST['magId'], $_POST['usrId']);
	break;
}
?>
