<?php
	include('php/top.php');
	
	if (!$my_simpleDbCheck->userIssetFromId($_GET['uid']))
		header("Location: szukaj.php");

/*	if(!empty($_GET['relacja'])) {
		$res = $my_usersRelations->ustaw_relacje(array('1st'=>$_SESSION['WiRunner_log_id'], '2nd'=> $_GET['uid']), ucfirst($_GET['relacja']));
		if($res == 1)
		 echo '<div class="ok_msg">Relacja pomyślnie zakutalizowana!</div>';
	}*/
?>
		<article>
			<section>
			<?php
				echo '<header class="entry-header">
						<h1 class="entry-title">Dodaj aktywność</h1>
				</header>';

				$res = $my_activities->formularzDodawania();
				if($res == -1) echo "Brak sportów do wybrania, błąd!";
			?>
				
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
