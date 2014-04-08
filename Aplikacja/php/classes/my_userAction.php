<?php
	final class my_userAction extends my_connDb {
		protected $pdo;

		function login($dane) {
			if(!my_validDate::wymagane(array($dane['email'], $dane['haslo'])))
				$bledy[] = 'Aby się zalogować podaj swój adres e-mail i hasło';

			if(!my_validDate::specjalne(array($dane['haslo'])))
				$bledy[] = 'Hasło może zawierać tylko litery i cyfry';

			if(!my_validDate::email(array($dane['email'])))
				$bledy[] = 'Podano niepoprawny adres e-mail';


			if(!isset($bledy)) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika, nr_rangi, email FROM uzytkownicy WHERE email LIKE BINARY :mail AND haslo LIKE BINARY :haslo');
					$stmt -> bindValue(':mail', $dane['email'], PDO::PARAM_STR);
					$stmt -> bindValue(':haslo', md5($dane['haslo']), PDO::PARAM_STR);
					$stmt -> execute();
					if($stmt -> rowCount() == 1) {
						$row = $stmt -> fetch();
						$_SESSION['WiRunner_log_id'] = $row['id_uzytkownika'];
						$_SESSION['WiRunner_policy'] = $row['nr_rangi'];
						$_SESSION['WiRunner_login'] = $row['email'];

						header("Location: konto.php");
					}
					else {
						$bledy[] = 'Podano niepoprawny login lub hasło';
					}
					
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				}

				if(!isset($bledy)) {
					// zmiana daty ostatniego logowania
					$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET ostatnie_logowanie = :lastlogin WHERE id_uzytkownika LIKE BINARY :logid');
					$stmt -> bindValue(':lastlogin', date("Y-m-d H:i:s"), PDO::PARAM_STR);
					$stmt -> bindValue(':logid', $row['id_uzytkownika'], PDO::PARAM_INT);
					$count = $stmt -> execute();
					$stmt -> closeCursor();
					unset($stmt);
				}
			}

			if(isset($bledy) && count($bledy) > 0)
				my_simpleMsg::show('Błedy logowania!', $bledy, 0);
		}

		function register($dane) {
			if(!my_validDate::wymagane(array($dane['email'], $dane['haslo'], $dane['eqhaslo'])))
				$bledy[] = 'Aby się zarejestrować wprowadź adres e-mail i hasło';

			if(!my_validDate::specjalne(array($dane['haslo'])))
				$bledy[] = 'Hasło może zawierać tylko litery i cyfry';

			if(!my_validDate::dlugoscmin(array($dane['haslo']), 4))
				$bledy[] = 'Minimalna długość hasła to cztery znaki';

			if(!my_validDate::wymagane(array($dane['zgoda'])))
				$bledy[] = 'Musisz zaakceptować regulamin serwisu';

			if(!my_validDate::email(array($dane['email'])))
				$bledy[] = 'Podano niepoprawny adres e-mail';

			if($dane['haslo'] != $dane['eqhaslo'])
				$bledy[] = 'Podane hasła nie zgadzają się';

			if(!my_validDate::wymagane(array($dane['plec'])))
				$bledy[] = 'Podaj swoją płeć';


			if(!isset($bledy)) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika FROM uzytkownicy WHERE email LIKE BINARY :email');
					$stmt -> bindValue(':email', $dane['email'], PDO::PARAM_STR);
					$stmt -> execute();
					if($stmt -> rowCount() == 1) {
						$bledy[] = 'Użytkownik o takim adresie e-mail już istnieje';
					}
					
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				}
			}

			if(!isset($bledy)) {
				try {
					$stmt = $this -> pdo -> prepare('INSERT INTO uzytkownicy(email, haslo, data_rejestracji, nr_rangi, plec) VALUES(:mail, :haslo, :data, :ranga, :plec)');
					$stmt -> bindValue(':mail', $dane['email'], PDO::PARAM_STR);
					$stmt -> bindValue(':haslo', md5($dane['haslo']), PDO::PARAM_STR);
					$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
					$stmt -> bindValue(':ranga', 3, PDO::PARAM_INT);
					$stmt -> bindValue(':plec', $dane['plec'], PDO::PARAM_STR);
					$count = $stmt -> execute();

					if ($count != 1)
						$bledy[] = 'Nieoczekiwany błąd podczas dodawania użytkownika';
					else if ($count == 1) {
					
$mail = $dane['email'];
$link = 'http://wi.ourtrips.pl/login.php?action=accountActiv&code='.md5($dane['haslo'].'a').'&mail='.$dane['email'];
$wiadomosc = <<<EOD
<html>
	<body>
		<h2>Aktywacja konta $mail!</h2>
		<p>Jeżeli rejestrowałeś się na naszej stronie, kliknij w poniższy link w celu aktywacji:</p>
		<a href="$link">$link</a>
	</body>
</html>
EOD;
						my_eMail::send($wiadomosc, 'wi.runner@gmail.com', $dane['email'], 'Aktywacja konta :: WiRunner', 'mailactiv');
						header("Location: login.php?msg=justReg");
					}
					
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				}
			}

			if(isset($bledy) && count($bledy) > 0)
				my_simpleMsg::show('Błedy rejestracji!', $bledy, 0);
		}

		function activation($dane) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT email, haslo FROM uzytkownicy WHERE email LIKE BINARY :mail');
				$stmt -> bindValue(':mail', $dane['mail'], PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() != 1) {
					return 0;
				}
				else {
					$row = $stmt -> fetch();
				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				return 0;
			}

			// poprawic aktywacje konta ze wzgledu na zlozony ciag

			//echo md5($row['haslo'].'a').'<br />';
			//echo $dane['code'];
			
			if($row['email'] == $dane['mail']) {
				// aktywowanie konta w bazie danych
				try {
					$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET potwierdzony_mail = 1 WHERE email LIKE BINARY :mail');
					$stmt -> bindValue(':mail', $dane['mail'], PDO::PARAM_STR);
					$count = $stmt -> execute();
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
					return 0;
				}
				if ($count == 1) {
					return 1;
				}
			}

		}
	}
?>
