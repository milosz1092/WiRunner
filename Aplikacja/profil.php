<?php
	include('php/top.php');
	
	if (!$my_simpleDbCheck->userIssetFromId($_GET['uid']))
		header("Location: szukaj.php");

	$userInfo = $my_simpleDbCheck->getUserInfo($_GET['uid']);

	if($_SESSION['WiRunner_log_id'] == 0 && $userInfo['widoczny_dla_gosci'] == 0)
		header("Location: login.php");

	if(!empty($_GET['relacja'])) {
		$res = $my_usersRelations->ustaw_relacje(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']), ucfirst($_GET['relacja']));
		if($res == 1)
		 echo '<div class="ok_msg">Relacja pomyślnie zakutalizowana!</div>';
		else if($res == 2)
		 echo '<div class="ok_msg">Użytkownik odblokowany!</div>';
		else if($res == 3)
		 echo '<div class="ok_msg">Użytkownik został usunięty z listy znajomych!</div>';
		else if($res == 4)
		 echo '<div class="ok_msg">Zaproszenie zostało odrzucone!</div>';
	}
	
	if(isset($_POST['dodajKomentarz'])){

	$resDodawania = $my_comments->dodajKoment(
								array(
									'id' => $_POST['id'],
								      	'komentarz' => $_POST['komentarz'],
								     	'rodzaj' => $_POST['rodzaj']
									));
	}else if(isset($_GET['action']) && $_GET['action'] == "usun_komentarz" && isset($_GET['koment_id']) && intval($_GET['koment_id']) )
	{
		$res = $my_comments->removeComment("doProfilu", $_GET['koment_id']);

		if($res == 1)
			echo '<div class="ok_msg">Komentarz pomyślnie usunięty!</div>';
		else if($res == 0)
		 echo '<div class="wrong_msg">Usuwanie zakończone niepowodzeniem!</div>';
		else if($res == -1)
		 echo '<div class="wrong_msg">Nie masz uprawnień!</div>';
	}

if ($_GET['uid'] != $_SESSION['WiRunner_log_id']){
	$rodzajRelacji = $my_usersRelations->zwroc_typ(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']));
//	echo $rodzajRelacji;
	}
	
	
?>
		<article>
			<section>
				<div style="float: left; width: 440px;" id="userHeader">
					<img style="display:inline-block;float:left;margin-right:20px;" src="img/web/unknow.jpg" alt="avatar" />
					<h2><?php
					if ($userInfo['imie'] == '' || $userInfo['nazwisko'] == '')
						echo $userInfo['email'];
					else
						echo $userInfo['imie'].' '.$userInfo['nazwisko'];
					?></h2>
					<? if(!empty($userInfo['motto'])) echo '<span style="margin-left: 1cm; font-style: italic; font-size: 12px;">'.$userInfo['motto'] .'</span>'; ?>
					<div style="margin-top:30px;">
						
						
						<?php
							if(isset($rodzajRelacji))
							{
								if($rodzajRelacji === 0)
									echo '<input type="button" value="Zablokuj" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=wróg\'" />';

								if(!in_array($rodzajRelacji, array("Wróg","Blokowany")))
									echo '<input type="button" value="Prywatna wiadomość" onclick="document.location.href=\'konto.php?subPage=poczta&action=writeMsg&uid='.$_GET['uid'].'\'" />';
								else    echo '<input type="button" value="Odblokuj" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=odblokuj\'" />';

								if($rodzajRelacji === "Przyjaciel")
									echo '<input type="button" value="Usuń znajomego" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=usun_znajomego\'" />';
				
								if($rodzajRelacji === "ZaproszenieWychodzace")
								echo '<input type="button" value="Zaproszenie wysłane">';
								else if(($rodzajRelacji === 0 || $rodzajRelacji === "ZaproszeniePrzychodzace"))
									echo '<input type="button" value="Dodaj znajomego" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=przyjaciel\'" />';
							}
							
						?>
					</div>
				

			<?php
					if(isset($resDodawania) && is_array($resDodawania))
					my_simpleMsg::show('Błedy danych!', $resDodawania, 0);
					echo $my_comments->formularzDodawania("doProfilu",$_GET['uid']);
	
					$my_comments->printComments("doProfilu", $_GET['uid']);		
			?>
			</div>
			<div style="float: right; width: 300px;">
			<?
			$id_aktywnosci = $my_activities->zwrocNajnowszeId($_GET['uid']);
			if(!empty($id_aktywnosci)){
				echo "<h1>Najnowsze aktywności:</h1>";
			} else {
				echo "Jak narazie brak aktywności!!";
			}

			foreach($id_aktywnosci as $id)
			{
				$my_activities->printActivity($id['id_aktywnosci']);
			}

			?>
			</div>

			</section>
		</article>
<?php
	include('php/bottom.php');
?>
