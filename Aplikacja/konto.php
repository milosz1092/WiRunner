<?php
	include('php/top.php');

	// akcja przy uzyciu linku aktywacyjnego (przy zalogowanym uzytkowniku)
	if(isset($_GET['action']) && $_GET['action'] == 'accountActiv' && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
		if ($my_userAction->activation(array('code' => $_GET['code'], 'mail' => $_GET['mail'])))
			echo '<div class="ok_msg">Twoje konto zostało aktywowane!</div>';
		else
			echo '<div class="wrong_msg">Błąd podczas aktywacji konta!</div>';
	}

// sprawdzenie, czy współrzędne nie są już czasem ustawione;
if(!$my_userAction->get_coordinates(1))
	echo '<a href="./wspolrzedne.php">Ustaw swoje współrzędne na mapie!</a>';


?>
<div id="big_contener">
	<div id="left_contener">
		<div class="left_menu">
			<h3>Moje konto</h3>
			<ul>
<?php
				foreach ($my_siteTitle->konto_links() as $link => $title) {
					echo '<li><a ';
					if (isset($_GET['subPage']) && $link == $_GET['subPage'])
						echo 'class="act" ';
					echo 'href="'.my_getFilename::normal().'?subPage='.$link.'">'.$title.'</a></li>';
				}
?>
			</ul>
		</div>
	</div>
	<div id="right_contener">
<?php
		if (isset($_GET['subPage'])) {
			switch($_GET['subPage']) {
				case 'trasy':
					// pobranie tras użytkownika, jeżeli takowe istnieją
					if ($my_userAction->get_tracks() == 0) {
						echo '<p>Nie posiadasz zapisanych tras...</p>';
					}
?>
				
<?php
				break;
			}
		}
?>
	</div>
</div>
<?php
	include('php/bottom.php');
?>
