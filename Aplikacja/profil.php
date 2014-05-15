<?php
	include('php/top.php');
	
	if (!$my_simpleDbCheck->userIssetFromId($_GET['uid']))
		header("Location: szukaj.php");

	$userInfo = $my_simpleDbCheck->getUserInfo($_GET['uid']);
	if(!empty($_GET['relacja'])) {
		$res = $my_usersRelations->ustaw_relacje(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']), ucfirst($_GET['relacja']));
		if($res == 1)
		 echo '<div class="ok_msg">Relacja pomyślnie zakutalizowana!</div>';
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

								if($rodzaj === 0)
									echo '<input type="button" value="Dodaj znajomego" onclick="document.location.href=\'profil.php?uid='.$_GET['uid'].'&relacja=przyjaciel\'" />';
						
							}
						?>
					</div>
				</div>
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
