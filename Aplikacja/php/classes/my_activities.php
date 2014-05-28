<?php
	final class my_activities extends my_connDb {
		protected $pdo;
		
		function validateDate($date, $format = 'Y-m-d H:i:s')
		{
		    $d = DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
		}

		function formularzDodawania($sporty, $dane=NULL)
		{
			if(empty($sporty)) return -1;

			$pola = array(
					array('nazwa_treningu','Nazwa treningu','text','45','req'),
				      	array('opis','Krótki opis','text','45'),
				     	array('tempo','Tempo km/h','text','4', 'req'),
					array('dystans','Dystans','text','6','req'),
					array('data_treningu','Data treningu','date','10','req')
			);

			echo '<form action="" method="post">
				<ul class="form_field">
				<label for="sport_id" style="text-align: right; padding-right: 10px;">Sport:</label>
					<select id="sport_id" name="sport_id">
					<option value="0">>>Wybierz sport</option>';

			foreach($sporty as $ele){
				echo '<option value="'.$ele['id_sportu'].'">'.$ele['nazwa_sportu'].'</option>';
			}

			echo '	</select>';
	
				
				foreach($pola as $ele)
				{
					echo '<li style="margin: 2px;">
						<label for="'.$ele[0].'" style="text-align: right; padding-right: 10px;">'.$ele[1].':</label>';
						echo	'<input type="'.$ele[2].'" id="'.$ele[0].'" name="'.$ele[0].'" value="'.(isset($dane)? $dane[$ele[0]] : ($ele[0]=='data_treningu')?date("Y-m-d"):"").'" maxlength="'.$ele[3].'" '.(isset($ele[4])? 'required="required"':"").'/>';

					    	echo '</li>';
				}
			echo '<input style="margin: 20px 0px 0px 140px;" type="submit" value="Dodaj aktywność" name="dodajAktywnosc"></ul></form>';
			echo '<script>';
				echo '$("#sport_id").focus();';
			echo '</script>';
		}

		function dodajAktywnosc($dane)
		{
			// najpierw prosta walidacja
			if(!isset($_SESSION['WiRunner_log_id']))
							$bledy[] = 'Musisz być zalogowany!';

						if($dane['sport_id'] == 0 || !isset($dane['nazwa_treningu']) || !isset($dane['tempo']) || !isset($dane['dystans']) || !isset($dane['data_treningu']))	$bledy[] = 'Wszystkie pola są wymagane!';
					else
						{
							if(strlen($dane['nazwa_treningu']) < 3 || strlen($dane['nazwa_treningu']) > 36)
								$bledy[] = 'Nazwa treningu powinna mieć od 3 do 45 znaków!';

							if(isset($dane['opis']) && (strlen($dane['opis']) < 3 || strlen($dane['opis']) > 45))
								$bledy[] = 'Opis powinn mieć od 3 do 45 znaków!';

							if(!floatval($dane['tempo']) || $dane['tempo'] < 0 || $dane['tempo'] > 50)
								$bledy[] = 'Nieprawidłowa wartość tempa!';

							if(!floatval($dane['dystans']) || $dane['dystans'] < 0 || $dane['dystans'] > 500)
								$bledy[] = 'Nieprawidłowa wartość dystansu!';

							if($this->validateDate($dane['data_treningu'], 'Y-m-d') === false)
								$bledy[] = 'Nieprawidłowa data treningu!';
						}
		
						if(isset($bledy) && count($bledy) > 0){
							my_simpleMsg::show('Błedy danych!', $bledy, 0);
							return -1;			
						}	
			
						try {
							$stmt = $this -> pdo -> prepare('INSERT INTO aktywnosci(nr_sportu, nr_uzytkownika, nazwa_treningu, opis, tempo, dystans, data_treningu, data_dodania) VALUES(:nr_sportu, :nr_uzytkownika, :nazwa_treningu, :opis, :tempo, :dystans, :data_treningu, :data_dodania)');
							$stmt -> bindValue(':nr_sportu', $dane['sport_id'], PDO::PARAM_STR);
							$stmt -> bindValue(':nr_uzytkownika', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);					
							$stmt -> bindValue(':nazwa_treningu', $dane['nazwa_treningu'], PDO::PARAM_STR);
							$stmt -> bindValue(':opis', $dane['opis'], PDO::PARAM_STR);
							$stmt -> bindValue(':tempo', $dane['tempo'], PDO::PARAM_STR);
							$stmt -> bindValue(':dystans', $dane['dystans'], PDO::PARAM_STR);
							$stmt -> bindValue(':data_treningu', $dane['data_treningu'], PDO::PARAM_STR);
							$stmt -> bindValue(':data_dodania', date("Y-m-d H:i:s"), PDO::PARAM_STR);
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
		
		function getUserInfo($id) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM uzytkownicy WHERE id_uzytkownika LIKE BINARY :id');
				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				
				$row = $stmt -> fetch();
				
				$stmt -> closeCursor();
				unset($stmt);
				return $row;
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				return 0;
			}
	
		}

		function getActivityById($id)
		{
			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM aktywnosci WHERE id_aktywnosci LIKE BINARY :id');
				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				
				$row = $stmt -> fetch();
				
				$stmt -> closeCursor();
				unset($stmt);
				return $row;
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				return 0;
			}
		}
		
		function getSport($id) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT nazwa_sportu FROM sporty WHERE id_sportu LIKE BINARY :id');
				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$stmt -> closeCursor();
				unset($stmt);
				
				return $row['nazwa_sportu'];
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
			}
		}
		
		function formatujCzas($czas)
		{
			return intval($czas / 3600).'g:'.intval(($czas - intval($czas / 3600) * 3600) / 60).'min:'.($czas % 60).'s';
		}

		function printActivity($id)
		{
			$dane = $this->getActivityById($id);
			$user_info = $this->getUserInfo($dane['nr_uzytkownika']);

			echo '<div class="aktywnosc">
				<img style="display:inline-block;float:left;margin-right:10px;" width="20" height="20" src="img/web/unknow.jpg" alt="avatar" />

<a href="profil.php?uid='.$dane['nr_uzytkownika'].'"><b>'.(isset($user_info['imie'])?$user_info['imie'] . ' ' . $user_info['nazwisko'] : $user_info['email']) . '</b></a> uprawiał <u>' . $this->getSport($dane['nr_sportu']) . '</u>. ';
			$czas = (3600 * $dane['dystans']) / $dane['tempo'];
//			$godz = intval($czas / 3600);
//			$min = intval(($czas - $godz * 3600) / 60);
//			$sec = $czas % 60;
			echo 'Przebył '. $dane['dystans'] . 'km w ' . $this->formatujCzas($czas) .'.
				<span style="padding-top: 20px; float: left; width: 40px;"><a href="aktywnosc.php?id='.$id.'">więcej...</a></span>
				<span style="padding-top: 30px; clear: both; float: right; text-align: right;">'.$dane['data_dodania'].'</span></div>';
		}

		function zwrocNajnowszeId($idZrodla)
		{
			try {
				$stmt = $this -> pdo -> prepare('SELECT id_aktywnosci FROM aktywnosci WHERE nr_uzytkownika IN ('.$idZrodla.') ORDER BY id_aktywnosci DESC LIMIT 5');
		//		$stmt -> bindValue(':id', $idZrodla, PDO::PARAM_STR);
				$stmt -> execute();

				$row = $stmt -> fetchAll();
				
				$stmt -> closeCursor();
				unset($stmt);
				return $row;
			}
			catch(PDOException $e) {
				return -1;
				//echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
			}

		}
	}
?>
