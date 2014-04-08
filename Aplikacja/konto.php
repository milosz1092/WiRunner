<?php
	include('php/top.php');

	// akcja przy uzyciu linku aktywacyjnego (przy zalogowanym uzytkowniku)
	if(isset($_GET['action']) && $_GET['action'] == 'accountActiv' && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
		if ($my_userAction->activation(array('code' => $_GET['code'], 'mail' => $_GET['mail'])))
			echo '<div class="ok_msg">Twoje konto zostało aktywowane!</div>';
		else
			echo '<div class="wrong_msg">Błąd podczas aktywacji konta!</div>';
	}
?>

<?php
	include('php/bottom.php');
?>
