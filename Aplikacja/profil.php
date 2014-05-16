<?php
	include('php/top.php');
	
	if (!$my_simpleDbCheck->userIssetFromId($_GET['uid']))
		header("Location: szukaj.php");

	$userInfo = $my_simpleDbCheck->getUserInfo($_GET['uid']);
	if(!empty($_GET['relacja'])) {
		$res = $my_usersRelations->ustaw_relacje(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']), ucfirst($_GET['relacja']));
		if($res == 1)
		 echo '<div class="ok_msg">Relacja pomyślnie zakutalizowana!</div>';
		else if($res == 2)
		 echo '<div class="ok_msg">Użytkownik odblokowany!</div>';
		else if($res == 3)
		 echo '<div class="ok_msg">Użytkownik został usunięty z listy znajomych!</div>';
	}
	
	if(isset($_POST['dodajKomentarz'])){

	$resDodawania = $my_comments->dodajKoment(
								array(
									'id' => $_POST['id'],
								      	'komentarz' => $_POST['komentarz'],
								     	'rodzaj' => $_POST['rodzaj']
									));
	}
	//$my_simpleDbCheck->userIssetFromId($_GET['uid']);	
?>
		<article>
			<section>
				<div id="userHeader">
					<img style="display:inline-block;float:left;margin-right:20px;" src="img/web/unknow.jpg" alt="avatar" />
					<h2><?php
					if ($userInfo['imie'] == '' || $userInfo['nazwisko'] == '')
						echo $userInfo['email'];
					else
						echo $userInfo['imie'].' '.$userInfo['nazwisko'];
					?></h2>
					<div style="margin-top:30px;">
						
						
						<?php
							if ($_GET['uid'] != $_SESSION['WiRunner_log_id']) {
						
								$rodzaj = $my_usersRelations->zwroc_typ(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']));
								if($rodzaj)
								echo "Wasza relacja: ". $rodzaj . "<br/>";
								if($rodzaj === 0)
									echo '<input type="button" value="Zablokuj" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=wróg\'" />';
								if($rodzaj !== "Wróg")
									echo '<input type="button" value="Prywatna wiadomość" onclick="document.location.href=\'konto.php?subPage=poczta&action=writeMsg&uid='.$_GET['uid'].'\'" />';
								else    echo '<input type="button" value="Odblokuj" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=odblokuj\'" />';

								if($rodzaj === "Przyjaciel")
									echo '<input type="button" value="Usuń znajomego" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=usun_znajomego\'" />';

								if($rodzaj === 0)
									echo '<input type="button" value="Dodaj znajomego" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=przyjaciel\'" />';
						
							}
						?>
					</div>
				</div>

			<?php
					if(isset($resDodawania) && is_array($resDodawania))
					my_simpleMsg::show('Błedy danych!', $resDodawania, 0);
					echo $my_comments->formularzDodawania("doProfilu",$_GET['uid']);
	
					$my_comments->printComments("doProfilu", $_GET['uid']);		
			?>
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
