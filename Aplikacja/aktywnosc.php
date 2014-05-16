<?php
	include('php/top.php');

	if(isset($_POST['dodajKomentarz'])){

	$resDodawania = $my_comments->dodajKoment(
								array(
									'id' => $_POST['id'],
								      	'komentarz' => $_POST['komentarz'],
								     	'rodzaj' => $_POST['rodzaj']
									));
	}

	if(empty($_GET['id']) || $my_activities->getActivityById($_GET['id']) == 0)
		header("Location: szukaj.php");
	
	$my_activities->printActivity($_GET['id']);
	
	if(isset($resDodawania) && is_array($resDodawania))
	my_simpleMsg::show('Błedy danych!', $resDodawania, 0);
	echo $my_comments->formularzDodawania("doAktywnosci",$_GET['id']);
	
	$my_comments->printComments("doAktywnosci", $_GET['id']);


	include('php/bottom.php');
?>
