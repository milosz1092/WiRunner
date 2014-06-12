<?php
	final class my_userAction extends my_connDb {
		protected $pdo;

// logowanie uzytkownika
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
					$count = $stmt -> rowCount();
					$row = $stmt -> fetch();
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
					return 0;
				}

			}

			if($count == 1) {
				if ($row['blokada'] == 1)
					$bledy[] = 'Twoje konto zostało zablokowane przez administratora';
				else {
					$_SESSION['WiRunner_log_id'] = $row['id_uzytkownika'];
					$_SESSION['WiRunner_policy'] = $row['nr_rangi'];
					$_SESSION['WiRunner_login'] = $row['email'];

					// zmiana daty ostatniego logowania
					$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET ostatnie_logowanie = :lastlogin WHERE id_uzytkownika LIKE BINARY :logid');
					$stmt -> bindValue(':lastlogin', date("Y-m-d H:i:s"), PDO::PARAM_STR);
					$stmt -> bindValue(':logid', $row['id_uzytkownika'], PDO::PARAM_INT);
					$count = $stmt -> execute();
					$stmt -> closeCursor();
					unset($stmt);

					header("Location: konto.php");
					return 1;
				}
			}
			else {
				$bledy[] = 'Podano niepoprawny login lub hasło';
			}

			if(isset($bledy) && count($bledy) > 0) {
				my_simpleMsg::show('Błedy logowania!', $bledy, 0);
				return 0;
			}
		}

// rejestracja uzytkownika
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

// aktywacja konta po kliknieciu w link aktywacyjny
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

// rzadanie resetu hasla
		function pass_reset($email) {
			try {
					$stmt = $this -> pdo -> prepare('INSERT INTO zadania_resetu_hasla(nr_uzytkownika, data_zadania) VALUES((SELECT id_uzytkownika FROM uzytkownicy WHERE email LIKE BINARY :mail), :data)');
					$stmt -> bindValue(':mail', $email, PDO::PARAM_STR);
					$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);

					$count = $stmt -> execute();
					if ($count != 1)
						$bledy[] = 'Podałeś niepoprawny adres e-mail';
						
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				$bledy[] = 'Podałeś nipoprawny adres e-mail';
			}
			if (isset($bledy)) {
				my_simpleMsg::show('Błedy podczas akcji resetu hasła!', $bledy, 0);
				return 0;
			}
			else
				return 1;
			
		}

// reset hasla - zmiana hasla
		function pass_resetNow($nowe, $mail, $kod) {
			if(!my_validDate::specjalne(array($nowe)))
				$bledy[] = 'Hasło może zawierać tylko litery i cyfry';

			if(!my_validDate::dlugoscmin(array($nowe), 4))
				$bledy[] = 'Minimalna długość hasła to cztery znaki';

			if($kod != md5($mail.'zXdfcmKs35Dc'))
				$bledy[] = 'Link resetujacy jest niepoprawny';

			if (!isset($bledy)) {
				try {
					$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET haslo = :haslo WHERE email LIKE BINARY :mail');
					$stmt -> bindValue(':haslo', md5($nowe), PDO::PARAM_STR);
					$stmt -> bindValue(':mail', $mail, PDO::PARAM_STR);
					$stmt -> execute();
					$count = $stmt -> rowCount();
					
					if ($count != 1)
						$bledy[] = 'Nie posiadamy takiego konta w bazie';

					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					$bledy[] = 'Błąd bazy danych';
				}

			}
			
			if (!isset($bledy)) {
				header("Location: login.php?msg=justReset");
				return 1;
			} else {
				my_simpleMsg::show('Błedy podczas akcji resetu hasła!', $bledy, 0);
				return 0;
			}
		}

// zmiana hasla
	function passChange($dane, $user) {
		if(!my_validDate::wymagane(array($dane['ch_haslo_cur'], $dane['ch_haslo_new'], $dane['ch_eq_haslo_new'])))
			$bledy[] = 'Podaj aktualne i nowe hasło';

		if(!my_validDate::specjalne(array($dane['ch_haslo_new'])))
			$bledy[] = 'Hasło może zawierać tylko litery i cyfry';

		if(!my_validDate::dlugoscmin(array($dane['ch_haslo_new']), 4))
			$bledy[] = 'Minimalna długość hasła to cztery znaki';

		if($dane['ch_haslo_new'] != $dane['ch_eq_haslo_new'])
			$bledy[] = 'Podane hasła nie zgadzają się';
	
		if(!isset($bledy)) {
			$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET haslo = :new_haslo WHERE id_uzytkownika LIKE BINARY :logid AND haslo LIKE BINARY :akt_haslo');
			$stmt -> bindValue(':new_haslo', md5($dane['ch_haslo_new']), PDO::PARAM_STR);
			$stmt -> bindValue(':akt_haslo', md5($dane['ch_haslo_cur']), PDO::PARAM_STR);
			$stmt -> bindValue(':logid', $user, PDO::PARAM_INT);
			$stmt -> execute();
			$count = $stmt -> rowCount();
			$stmt -> closeCursor();
			unset($stmt);

			if ($count != 1) {
				$bledy[] = 'Podałeś niepoprawne dane';
			}
		}
		
		if (isset($bledy)) {
			my_simpleMsg::show('Nie udało się zmienić hasła!', $bledy, 0);
			return 0;
		}
		else
			return 1;
	}

// usuwanie konta
	function delAcount($dane, $user) {
		$count = 0;
		try {
			$stmt = $this -> pdo -> prepare('DELETE FROM uzytkownicy WHERE id_uzytkownika LIKE BINARY :logid AND haslo LIKE BINARY :akt_haslo');
			$stmt -> bindValue(':logid', $user, PDO::PARAM_INT);
			$stmt -> bindValue(':akt_haslo', md5($dane['delaco_haslo_cur']), PDO::PARAM_STR);
			$stmt -> execute();
			$count = $stmt->rowCount();
			$stmt -> closeCursor();
			unset($stmt);
		}
		catch(PDOException $e) {
			$bledy[] = 'Podałeś niepoprawne dane';
		}

		if ($count != 1)
			$bledy[] = 'Podałeś niepoprawne dane';
			
		if (isset($bledy)) {
			my_simpleMsg::show('Nie udało się usunąć konta!', $bledy, 0);
			return 0;
		}
		else
			return 1;
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
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
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
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;
		}

// dodawanie nowej trasy
		function add_track($dane) {
	
			if(!isset($_SESSION['WiRunner_log_id']))
				$bledy[] = 'Musisz być zalogowany!';

			if(!isset($dane['nazwa']) || !isset($dane['przebieg']) || !is_numeric($dane['dlugosc']) || !isset($dane
['punkty']))	$bledy[] = 'Nieprawidłowe wartości niektórych pól!';
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
				$stmt -> bindValue(':dlugosc', $dane['dlugosc']/1000, PDO::PARAM_STR);
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


// edytowanie trasy
		function edit_track($dane) {
			if(!isset($_SESSION['WiRunner_log_id']))
				$bledy[] = 'Musisz być zalogowany!';

			if(!isset($dane['nazwa']) || !isset($dane['id_trasy']) || !isset($dane['przebieg']) || !is_numeric($dane['dlugosc']) || !isset($dane['punkty']))	$bledy[] = 'Nieprawidłowe wartości niektórych pól!';
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
				$stmt = $this -> pdo -> prepare('UPDATE trasy SET nazwa_trasy=:nazwa, dlugosc_trasy=:dlugosc, przebieg_trasy=:przebieg, punkty_trasy=:punkty WHERE id_trasy=:id_trasy');

//				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
				$stmt -> bindValue(':nazwa', $dane['nazwa'], PDO::PARAM_STR);
				$stmt -> bindValue(':dlugosc', $dane['dlugosc']/1000, PDO::PARAM_STR);
				$stmt -> bindValue(':przebieg', $dane['przebieg'], PDO::PARAM_STR);
				$stmt -> bindValue(':punkty', $dane['punkty'], PDO::PARAM_STR);
//				$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt -> bindValue(':id_trasy', $dane['id_trasy'], PDO::PARAM_INT);
				$stmt -> execute();

				
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;
		}
		
// pobieranie listy tras użytkownika
		function get_tracks($user_id=0) {
			if($user_id == 0) $user_id = $_SESSION['WiRunner_log_id'];
			try {

				$stmt = $this -> pdo -> prepare('SELECT id_trasy, nazwa_trasy, dlugosc_trasy FROM trasy WHERE nr_uzytkownika LIKE BINARY :numer_usera');
				$stmt -> bindValue(':numer_usera', $user_id, PDO::PARAM_INT);
				$stmt -> execute();
								
				if($stmt -> rowCount() == 0) {

					return 0;
				}
				else {
					echo "<ul>";
						

						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<li style="width: 340px;"><a href="./trasy.php?id='.$row['id_trasy'].'">'.$row['nazwa_trasy'].'</a> (dystans '.$row['dlugosc_trasy'].'km)';


					if($user_id == $_SESSION['WiRunner_log_id']) echo '<span style="font-size: 10px; float: right;"><a href="'.my_getFilename::normal().'?subPage=trasy&action=usun&id='.$row['id_trasy'].'">usuń trasę</a></span>';
					else  echo '<span style="font-size: 10px; float: right;"><a href="'.my_getFilename::normal().'?subPage=trasy&action=kopiuj&id='.$row['id_trasy'].'">kopiuj trasę</a></span>';		
					
					echo '</li>';
						}
					echo 	"</ul>";

				}
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {

				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return 1;


		}
// get_tracks w wersji zwracającej tablicę wyników
function getTracks($user_id=0) {
			if($user_id == 0) $user_id = $_SESSION['WiRunner_log_id'];
			try {

				$stmt = $this -> pdo -> prepare('SELECT id_trasy, nazwa_trasy, dlugosc_trasy FROM trasy WHERE nr_uzytkownika LIKE BINARY :numer_usera');
				$stmt -> bindValue(':numer_usera', $user_id, PDO::PARAM_INT);
				$stmt -> execute();
								
				if($stmt -> rowCount() == 0) {

					return 0;
				}
				
				$row = $stmt->fetchAll();

				$stmt -> closeCursor();
				unset($stmt);
				return $row;
			}
			catch(PDOException $e) {

				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
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
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
				
			return $row;

		}	

		function removeTrack($id_trasy)
		{
			$trasa = $this->get_track($id_trasy);
			if($trasa && $trasa['nr_uzytkownika'] != $_SESSION['WiRunner_log_id'] /* && niezalogowany jako admin/moderator */)
				return -1;
			try {
				$stmt = $this -> pdo -> prepare('DELETE FROM trasy WHERE id_trasy=:id_trasy');
				$stmt -> bindValue(':id_trasy', $id_trasy, PDO::PARAM_STR);
				$stmt -> execute();

				$stmt -> closeCursor();
				unset($stmt);
				return 1;
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
		 }
		
		function copyTrack($id_trasy)
		{
			$trasa = $this->get_track($id_trasy);
			if(!$trasa || $trasa['nr_uzytkownika'] == $_SESSION['WiRunner_log_id']/* && niezalogowany jako admin/moderator */)
				return -1;
			/* jeszcze można sprawdzić, czy ktoś nie próbuje kopiować trasy osoby, która go blokuje ;) */

			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM trasy WHERE id_trasy=:id_trasy');
				$stmt -> bindValue(':id_trasy', $id_trasy, PDO::PARAM_STR);
				$stmt -> execute();

				$dane = $stmt -> fetch(PDO::FETCH_ASSOC);

				$stmt -> closeCursor();
				unset($stmt);

				$stmt = $this -> pdo -> prepare('INSERT INTO trasy VALUES   (0, :nr_usera,:nazwa,:dlugosc,:przebieg,:punkty, :data)');

				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
				$stmt -> bindValue(':nazwa', $dane['nazwa_trasy'], PDO::PARAM_STR);
				$stmt -> bindValue(':dlugosc', $dane['dlugosc_trasy'], PDO::PARAM_STR);
				$stmt -> bindValue(':przebieg', $dane['przebieg_trasy'], PDO::PARAM_STR);
				$stmt -> bindValue(':punkty', $dane['punkty_trasy'], PDO::PARAM_STR);
				$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt -> execute();

				
				$stmt -> closeCursor();
				unset($stmt);

				return 1;
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return 0;
			}
		 
		}

//	formularz edycji swoich danych
		function profil_edit($userInfo) {
			$pola = array(
					array('imie','Imię','text','36','req'),
				      	array('nazwisko','Nazwisko','text','45','req'),
				     	array('waga','Waga','number','3'),
					array('wzrost','Wzrost','number','3'),
					array('miejscowosc','Miejscowość','text','45','req'),
					array('data_urodzenia','Data urodzenia','date',''),
					array('motto','Motto','textarea','245')
					);

			

			echo '<header class="entry-header">
					<h1 class="entry-title">Edytuj dane</h1>
			</header>
				<form action="" method="post">
				<ul class="form_field">';
				foreach($pola as $ele)
				{
					echo '<li>
						<label for="'.$ele[0].'" style="text-align: right; padding-right: 10px;">'.$ele[1].':</label>';
					if($ele[2] != "textarea")
						echo	'<input type="'.$ele[2].'" id="'.$ele[0].'" name="'.$ele[0].'" value="'.$userInfo[$ele[0]].'" maxlength="'.$ele[3].'" '.(isset($ele[4])? 'required="required"':"").'/>';
					else	echo	'<textarea id="'.$ele[0].'" name="'.$ele[0].'" maxlength="'.$ele[3].'"/>'.$userInfo[$ele[0]].'</textarea>';
					    	echo '</li>';
				}
			echo '<br/><li style="margin: 2px;">
				<label for="prywatnosc" style="text-align: right; padding-right: 10px;">Prywatność :</label>
				<select name="widoczny_dla_gosci">
					<option value="1" '.($userInfo['widoczny_dla_gosci']==1?'selected':'').'>profil widoczny dla gości</option>
					<option value="0" '.($userInfo['widoczny_dla_gosci']==0?'selected':'').'>profil niewidoczny dla gości</option>
				</select></li>';

			echo '<input style="margin: 20px 0px 0px 140px;" type="submit" value="Akutalizuj dane" name="edytujDane"></ul></form>';
			echo '<script>';
				echo '$("#imie").focus();';
			echo '</script>';
		}

		function profile_update($dane){
			// najpierw prosta walidacja
			if(!isset($_SESSION['WiRunner_log_id']))
							$bledy[] = 'Musisz być zalogowany!';

						if(!isset($dane['imie']) || !isset($dane['nazwisko']) || !isset($dane['miejscowosc']))	$bledy[] = 'Pola imię, nazwisko i miejscowość są wymagane!';
					else
						{
							if(strlen($dane['imie']) < 3 || strlen($dane['imie']) > 36)
								$bledy[] = 'Imię powinno mieć od 3 do 36 znaków!';

							if(strlen($dane['nazwisko']) < 2 || strlen($dane['nazwisko']) > 45)
								$bledy[] = 'Nazwisko powinno mieć od 3 do 45 znaków!';

							if(strlen($dane['miejscowosc']) < 3 || strlen($dane['miejscowosc']) > 45)
								$bledy[] = 'Nazwa miejscowości powinna mieć od 3 do 45 znaków!';
						}

						if(!empty($dane['waga'])){
							if(!intval($dane['waga']) || $dane['waga'] < 40 || $dane['waga'] > 220)
								$bledy[] = 'Minimalna waga użytkownika to 40kg, max 220!!';
}
						if(!empty($dane['wzrost'])){
							if(!intval($dane['wzrost']) || $dane['wzrost'] < 130 || $dane['wzrost'] > 240)
								$bledy[] = 'Minimalny wzrost użytkownika to 130cm, max 240!!';
}
						if(!empty($dane['motto'])){
							if(strlen($dane['motto']) < 5 || strlen($dane['nazwisko']) > 245)
								$bledy[] = 'Motto powinno mieć od 5 do 245 znaków!';

}
		
						if(isset($bledy) && count($bledy) > 0){
							my_simpleMsg::show('Błedy danych!', $bledy, 0);
							return -1;			
						}	
			
						try {
							$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET imie=:imie, nazwisko=:nazwisko, waga=:waga, wzrost=:wzrost, miejscowosc=:miejscowosc, motto=:motto, widoczny_dla_gosci=:widoczny_dla_gosci, data_urodzenia=:data_urodzenia WHERE id_uzytkownika=:id_uzytkownika');

							$stmt -> bindValue(':id_uzytkownika', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
							$stmt -> bindValue(':imie', ucfirst($dane['imie']), PDO::PARAM_STR);
							$stmt -> bindValue(':nazwisko', ucwords($dane['nazwisko']), PDO::PARAM_STR);
							$stmt -> bindValue(':waga', $dane['waga'], PDO::PARAM_INT);
							$stmt -> bindValue(':wzrost', $dane['wzrost'], PDO::PARAM_INT);
							$stmt -> bindValue(':miejscowosc', ucwords($dane['miejscowosc']), PDO::PARAM_STR);
							$stmt -> bindValue(':motto', $dane['motto'], PDO::PARAM_STR);
							$stmt -> bindValue(':widoczny_dla_gosci', $dane['widoczny_dla_gosci'], PDO::PARAM_STR);
							$stmt -> bindValue(':data_urodzenia', $dane['data_urodzenia'], PDO::PARAM_STR);
							$stmt -> execute();

				
							$stmt -> closeCursor();
							unset($stmt);
						}
						catch(PDOException $e) {
							//echo '<p>Wystąpił błąd biblioteki PDO</p>';
							return 0;
						}
		}

		function lockToggle($type, $uId) {
			try {
				$stmt = $this -> pdo -> prepare('UPDATE uzytkownicy SET blokada = :toggle WHERE id_uzytkownika LIKE BINARY :id');
				$stmt -> bindValue(':toggle', $type, PDO::PARAM_INT);
				$stmt -> bindValue(':id', $uId, PDO::PARAM_INT);
				$stmt -> execute();
				$count = $stmt -> rowCount();

				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				//$bledy[] = 'Błąd bazy danych';
				return 0;
			}

			if ($count == 1)
				return 1;
			else
				return 0;
		}
		
	}
?>
