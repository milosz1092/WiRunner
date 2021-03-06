<?php
	final class my_comments extends my_connDb {
		protected $pdo;
		function formularzDodawania($typ=0, $id=0)
		{
			if($typ === 0 || $id === 0) return -2;
			if(($typ != "doProfilu" && $typ != "doAktywnosci")) return -1;
 			echo '<div style="margin-top:5px;">';
			echo '<form action="" method="post">
					<input style="width: 220px;" type="text" id="komentarz" name="komentarz" placeholder="Napisz komentarz" maxlength="245" required="required"/>
					<br/><input style=" font-decoration: bold; margin-left: 70px;" type="submit" value="DODAJ KOMENTARZ" name="dodajKomentarz">
					<input type="hidden" name="rodzaj" value="'.$typ.'">
					<input type="hidden" name="id" value="'.$id.'">
				</form>
			</div>';
		}

		function dodajKoment($dane)
		{
			// najpierw prosta walidacja
			if(!isset($_SESSION['WiRunner_log_id']))
							$bledy[] = 'Musisz być zalogowany!';

						if(!isset($dane['id']) || !isset($dane['komentarz']) || !isset($dane['rodzaj']) || ($dane['rodzaj'] != "doProfilu" && $dane['rodzaj'] != "doAktywnosci"))
						$bledy[] = 'Nie przesłano wszystkich wymaganych danych!';
					else
						{
							if(strlen($dane['komentarz']) == 0 || strlen($dane['komentarz']) > 245)
								$bledy[] = 'Komentarz może mieć maksymalnie 245 znaki!';

						}
		
						if(isset($bledy) && count($bledy) > 0){
							return $bledy;			
						}	
			
						try {
				if($dane['rodzaj'] == "doAktywnosci")
							$stmt = $this -> pdo -> prepare('INSERT INTO  komentarze_do_aktywnosci(nr_aktywnosci, nr_uzytkownika, tresc, data_dodania) VALUES(:nr_id, :nr_uzytkownika, :tresc, :data_dodania)');
				else if($dane['rodzaj'] == "doProfilu")
							$stmt = $this -> pdo -> prepare('INSERT INTO  komentarze_do_profilu(nr_profilu, nr_uzytkownika, tresc, data_dodania) VALUES(:nr_id, :nr_uzytkownika, :tresc, :data_dodania)');
				else return -1;

							$stmt -> bindValue(':nr_id', $dane['id'], PDO::PARAM_STR);
							$stmt -> bindValue(':nr_uzytkownika', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);					
							$stmt -> bindValue(':tresc', $dane['komentarz'], PDO::PARAM_STR);
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

		function getCommentsById($typ=0, $id=0)
		{
			if($typ === 0 || $id === 0) return -1;
			if(($typ != "doProfilu" && $typ != "doAktywnosci")) return -1;

			try {
			if($typ == "doProfilu")
				$stmt = $this -> pdo -> prepare('SELECT * FROM komentarze_do_profilu WHERE nr_profilu LIKE BINARY :id');
			else if($typ == "doAktywnosci")
				$stmt = $this -> pdo -> prepare('SELECT * FROM komentarze_do_aktywnosci WHERE nr_aktywnosci LIKE BINARY :id');

				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				
				$row = $stmt -> fetchAll();
				
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

		function getCommentById($typ=0, $id=0)
		{
			if($typ === 0 || $id === 0) return -1;
			if(($typ != "doProfilu" && $typ != "doAktywnosci")) return -1;

			try {
			if($typ == "doProfilu")
				$stmt = $this -> pdo -> prepare('SELECT * FROM komentarze_do_profilu WHERE id_komentarza LIKE BINARY :id');
			else if($typ == "doAktywnosci")
				$stmt = $this -> pdo -> prepare('SELECT * FROM komentarze_do_aktywnosci WHERE id_komentarza LIKE BINARY :id');

				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				
				if($stmt -> rowCount() == 0) {
					$stmt -> closeCursor();
					unset($stmt);
					return 0;
				}
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
	

		function printComments($typ=0, $id=0)
		{
			if($typ === 0 || $id === 0) return -1;
			if(($typ != "doProfilu" && $typ != "doAktywnosci")) return -1;

			$dane = $this->getCommentsById($typ, $id);
			echo '<div style="display:block;float:none;clear:both;padding-top:10px;">';
			foreach($dane as $ele)
			{
				$user_info = $this->getUserInfo($ele['nr_uzytkownika']);

				echo '<div class="komentarz">
					<img style="display:inline-block;float:left;margin-right:10px;" width="20" height="20" src="img/web/unknow.jpg" alt="avatar" />

	<a href="profil.php?uid='.$ele['nr_uzytkownika'].'"><b>'.(isset($user_info['imie'])?$user_info['imie'] . ' ' . $user_info['nazwisko'] : $user_info['email']) . '</b></a> '.$ele['data_dodania'].' napisał:';
				echo '<span style="padding-top: 40px; clear: both; float: left;">'.$ele['tresc'].'</span>';
				echo '<span style="padding-left: 120px;"><a href="'.my_getFilename::normal().'?'.($typ=="doProfilu"?'u':'').'id='.$id.'&action=usun_komentarz&koment_id='.$ele['id_komentarza'].'">usuń</a></span></div>';
			}
			echo '</div>';
		}

		function removeComment($typ=0, $id=0)
		{
			if($typ === 0 || $id === 0) return -1;
			$comment = $this->getCommentById($typ,$id);
			if($typ == "doAktywnosci")
			{
				$stmt = $this -> pdo -> prepare('SELECT * FROM aktywnosci WHERE id_aktywnosci =:id');
				$stmt -> bindValue(':id', $comment['nr_aktywnosci'], PDO::PARAM_STR);
				$stmt -> execute();
				$row = $stmt -> fetch(PDO::FETCH_ASSOC);

				$stmt -> closeCursor();
				unset($stmt);
			}
	
			if(!$comment || ($comment['nr_uzytkownika'] != $_SESSION['WiRunner_log_id'] && (($typ == "doProfilu" && $comment['nr_profilu'] != $_SESSION['WiRunner_log_id']) || ($typ == "doAktywnosci" && $row['nr_uzytkownika'] != $_SESSION['WiRunner_log_id']))))
				return -1;

			try {
				if($typ == "doProfilu")
				$stmt = $this -> pdo -> prepare('DELETE FROM komentarze_do_profilu WHERE id_komentarza LIKE BINARY :id');
			else if($typ == "doAktywnosci")
				$stmt = $this -> pdo -> prepare('DELETE FROM komentarze_do_aktywnosci WHERE id_komentarza LIKE BINARY :id');
				$stmt -> bindValue(':id', $id, PDO::PARAM_STR);
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

		function czyJestJuzPolubione($id=0)
		{
			if($id === 0) return -1;
			
				try {
					$stmt = $this -> pdo -> prepare('SELECT * FROM polubienia WHERE  nr_uzytkownika LIKE BINARY :user_id AND nr_aktywnosci LIKE BINARY :nr_id');

							$stmt -> bindValue(':nr_id', $id, PDO::PARAM_STR);
							$stmt -> bindValue(':user_id', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);					
							$stmt -> execute();
							if($stmt -> rowCount() == 0)
							return 0;

							$stmt -> closeCursor();
							unset($stmt);
							return 1;
						}
						catch(PDOException $e) {
							//echo '<p>Wystąpił błąd biblioteki PDO</p>';
							return 2;
						}
		}


		function dodajPolubienie($id=0)
		{
			if($id === 0) return -1;
			
				try {
					$stmt = $this -> pdo -> prepare('INSERT INTO  polubienia(nr_uzytkownika, nr_aktywnosci, data_polubienia) VALUES(:nr_uzytkownika, :nr_id, :data_dodania)');
				

							$stmt -> bindValue(':nr_id', $id, PDO::PARAM_STR);
							$stmt -> bindValue(':nr_uzytkownika', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);					
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

		function idPolubionych($id=0)
		{
			if($id === 0) return -1;
			
				try {
					$stmt = $this -> pdo -> prepare('SELECT * FROM polubienia WHERE nr_uzytkownika LIKE BINARY :id');
				
					$stmt -> bindValue(':id', $id, PDO::PARAM_STR);
					$stmt -> execute();
					$row = $stmt -> fetchAll();

					$stmt -> closeCursor();
					unset($stmt);
					return $row;
				}
				catch(PDOException $e) {
					//echo '<p>Wystąpił błąd biblioteki PDO</p>';
					return 0;
				}
		}
	}

?>
