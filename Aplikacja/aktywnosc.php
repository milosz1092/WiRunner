<?php
	include('php/top.php');

	if(empty($_GET['id']) || ($dane=$my_activities->getActivityById($_GET['id'])) == 0)
		header("Location: szukaj.php");

	if($_SESSION['WiRunner_log_id'] == 0 && $dane['widoczna_dla_gosci'] == 0)
		header("Location: login.php");

	if(isset($_POST['dodajKomentarz'])){

	$resDodawania = $my_comments->dodajKoment(
								array(
									'id' => $_POST['id'],
								      	'komentarz' => $_POST['komentarz'],
								     	'rodzaj' => $_POST['rodzaj']
									));
	}


	
	$my_activities->printActivity($_GET['id']);
	
	if(isset($resDodawania) && is_array($resDodawania))
	my_simpleMsg::show('Błedy danych!', $resDodawania, 0);
	echo $my_comments->formularzDodawania("doAktywnosci",$_GET['id']);
	
	$my_comments->printComments("doAktywnosci", $_GET['id']);


	include('php/bottom.php');
?>
