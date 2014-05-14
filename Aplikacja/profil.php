<?php
	include('php/top.php');
	
	if (!$my_simpleDbCheck->userIssetFromId($_GET['uid']))
		header("Location: szukaj.php");

	$userInfo = $my_simpleDbCheck->getUserInfo($_GET['uid']);
	
	
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
						?>
								<input type="button" value="Zablokuj" onclick="blockUser(<?php echo $_SESSION['WiRunner_log_id'].', '.$_GET['uid']; ?>)" />
								<input type="button" value="Prywatna wiadomość" onclick="document.location.href='konto.php?subPage=poczta&action=writeMsg&uid=<?php echo $_GET['uid']; ?>';" />
								<input type="button" value="Dodaj znajomego" onclick="sendInvite(<?php echo $_SESSION['WiRunner_log_id'].', '.$_GET['uid']; ?>)" />
						<?php
							}
						?>
					</div>
				</div>
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
