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
					$last_id = $this->pdo->lastInsertId();
					
$mail = $dane['email'];
$link = 'http://wi.ourtrips.pl/login.php?action=accountActiv&code='.md5($last_id.'zXdfcmKs35Dc').'&mail='.$dane['email'];
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
				$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika, email, haslo FROM uzytkownicy WHERE email LIKE BINARY :mail');
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

			$from_code = $dane['code'];
			$from_db = md5($row['id_uzytkownika'].'zXdfcmKs35Dc');
			
			if($from_db == $from_code) {
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

// pobieranaie domyślnych współrzędnych użytkownika
		function get_coordinates($p = 0) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT szerokosc, dlugosc FROM wspolrzedne WHERE nr_usera=:numer_usera');
				$stmt -> bindValue(':numer_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() != 1) {
					return 0;
				}
				else {
					if($p == 1) return 1;
					$row = $stmt -> fetch();
				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return array($row['szerokosc'], $row['dlugosc']);

		}

// ustawianie domyślnych współrzędnych użytkownika
		function set_coordinates($dane) {
	
			if(!isset($_SESSION['WiRunner_log_id']))
				$bledy[] = 'Musisz być zalogowany!';

			if(!isset($dane['szerokosc']) || !isset($dane['dlugosc']) || !is_numeric($dane['szerokosc']) || !is_numeric($dane
['szerokosc']))	$bledy[] = 'Wprowadzono nieprawidłowe wartości wspołrzędnych!';
		else
			{
				if(abs($dane['szerokosc']) > 90)
					$bledy[] = 'Nieprawidłowa wartość szerokości!';
			
				if(abs($dane['dlugosc']) > 180)
					$bledy[] = 'Nieprawidłowa wartość długości!';
			}
		
			if(isset($bledy) && count($bledy) > 0){
				my_simpleMsg::show('Błedy danych!', $bledy, 0);
				return 0;			
			}	
			
			try {
				$stmt = $this -> pdo -> prepare('INSERT INTO wspolrzedne VALUES (:nr_usera,:szerokosc,:dlugosc,:data)

  ON DUPLICATE KEY UPDATE szerokosc=:szerokosc, dlugosc=:dlugosc, data_ustawienia=:data');
				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
				$stmt -> bindValue(':szerokosc', $dane['szerokosc'], PDO::PARAM_STR);
				$stmt -> bindValue(':dlugosc', $dane['dlugosc'], PDO::PARAM_STR);
				$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() != 1) {
					// return 0;
				}
				else {
					$row = $stmt -> fetch();
				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;
		}

// dodawanie nowej trasy
		function add_track($dane) {
	
			if(!isset($_SESSION['WiRunner_log_id']))
				$bledy[] = 'Musisz być zalogowany!';

			if(!isset($dane['nazwa']) || !isset($dane['przebieg']) || !is_numeric($dane['dlugosc']) || !isset($dane
['punkty']))	$bledy[] = 'Nieprawidłowe wartości niektóych pól!';
		else
			{
				if(strlen($dane['nazwa']) < 3 || strlen($dane['nazwa']) > 36)
					$bledy[] = 'Długość nazwy powinna zawierać od 3 do 36 znaków!';
			
				// .. tutaj można jeszcze powymyślać jeszcze jakieś warunki
			}
		
			if(isset($bledy) && count($bledy) > 0){
				my_simpleMsg::show('Błedy danych!', $bledy, 0);
				return 0;			
			}	
			
			try {
				$stmt = $this -> pdo -> prepare('INSERT INTO trasy VALUES   
(0, :nr_usera,:nazwa,:dlugosc,:przebieg,:punkty, :data)');

				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
				$stmt -> bindValue(':nazwa', $dane['nazwa'], PDO::PARAM_STR);
				$stmt -> bindValue(':dlugosc', $dane['dlugosc'], PDO::PARAM_STR);
				$stmt -> bindValue(':przebieg', $dane['przebieg'], PDO::PARAM_STR);
				$stmt -> bindValue(':punkty', $dane['punkty'], PDO::PARAM_STR);
				$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt -> execute();
				
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;
		}

		function get_tracks($user_id=0) {
			if($user_id == 0) $user_id = $_SESSION['WiRunner_log_id'];
			try {
				$stmt = $this -> pdo -> prepare('SELECT id_trasy, nazwa_trasy, data_dodania FROM trasy WHERE nr_uzytkownika=:numer_usera');
				$stmt -> bindValue(':numer_usera', $user_id, PDO::PARAM_STR);
				$stmt -> execute();
								
				if($stmt -> rowCount() == 0) {
					return 0;
				}
				else {
					echo "<strong>Twoje trasy:</strong>
						<ul>";
						
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<li><a href="./trasy.php?id='.$row['id_trasy'].'">'.$row['nazwa_trasy'].'</a> (ostatnio edytowana '.$row['data_dodania'].')</li>';
						}
					echo 	"</ul>";
				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;

		}
		function get_track($track_id) {

			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM trasy WHERE id_trasy=:id_trasy');
				$stmt -> bindValue(':id_trasy', $track_id, PDO::PARAM_STR);
				$stmt -> execute();
								
				if($stmt -> rowCount() == 0) {
					return 0;
				}
				else {
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return $row;

		}	
	}
?>
