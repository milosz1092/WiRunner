<?php
	final class my_usersRelations extends my_connDb {
		protected $pdo;
	
		function zwroc_typ($id_userow)
		{
			// Zwraca albo nazwę relacji, albo 0 w przypadku braku.
			// Rodzaje relacji muszą zostać dodane do bazy, wymagane relacje to:
			// Przyjaciel, Wróg;
			// Zwracane warości: 0, Przyjaciel, Wróg, ZaproszeniePrzychodzace, ZaproszenieWychodzace, Blokowany, Zablokowany
			// Blokowany - u1 jest blokowany przez u2
			// Zablokowany - u2 jest blokowany przez u1
			// Wróg - wzajemna blokada
			
			try {
				$stmt = $this -> pdo -> prepare('
									SELECT relacja FROM relacje_uzytkownikow, rodzaje_relacji 
									WHERE	id_relacji LIKE BINARY  nr_rodzaju AND 
										nr_pierwszego LIKE BINARY :nr_pierwszego AND 
										nr_drugiego LIKE BINARY :nr_drugiego'
								);
				$stmt -> bindValue(':nr_pierwszego', $id_userow['1st'], PDO::PARAM_STR);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> execute();

				$row = $stmt -> fetch();

				$stmt -> closeCursor();
				unset($stmt);

				$stmt = $this -> pdo -> prepare('
									SELECT relacja FROM relacje_uzytkownikow, rodzaje_relacji 
									WHERE	id_relacji LIKE BINARY  nr_rodzaju AND 
										nr_pierwszego LIKE BINARY :nr_drugiego AND 
										nr_drugiego LIKE BINARY :nr_pierwszego'
								);
				$stmt -> bindValue(':nr_pierwszego', $id_userow['1st'], PDO::PARAM_STR);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> execute();

				$row2 = $stmt -> fetch();

				$stmt -> closeCursor();
				unset($stmt);

				if(isset($row['relacja']) && isset($row2['relacja'])){
					// relacja jest ustawiona u dwóch userów
					// mogą być wrogami, albo przyjaciółmi
					
					if($row['relacja'] == $row2['relacja'])
					return $row['relacja'];
				}
				else if(isset($row['relacja']))
				{
					switch($row['relacja'])
					{
						case "Przyjaciel":
							return "ZaproszenieWychodzace";
						break;
						case "Wróg":
							return "Blokowany";
						break;
					}
				}
				else if(isset($row2['relacja']))
				{
					switch($row2['relacja'])
					{
						case "Przyjaciel":
							return "ZaproszeniePrzychodzace";
						break;
						case "Wróg":
							return "Zablokowany";
						break;
					}
				}

				
				return "0";
				
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
				}
		}

		

		function znajdz_userow_w_relacji($id_uzytkownika, $relacja)
		{
			// Zwraca w tablicy id użytkowników spełniających podane kryterium.
			// W przypadku nie braku rekordów spełniających dane kryterium zwracane jest zero.
	
			// Prawidłowe wartości zmiennej $relacja to:
			$obslugiwaneRelacje = array("Przyjaciel", "Zaproszony", "Zaproszeni", "Zablokowani", "Wrog");

			// Przyjaciel - wzajemna przyjaźń
			// Zaproszony - osoby, które zaprosiły użytkownika o id $id_uzytkownika
			// Zaproszeni - osoby zaproszone przez $id_uzytkownika
			// Zablokowani - osoby ustawione jako wróg przez $id_uzytkownika, bez odwzajemnienia
			// Wrog - wzajemna blokada

			if(!in_array($relacja, $obslugiwaneRelacje)) return 0;

			$szukanaRelacja = in_array($relacja, array("Przyjaciel", "Zaproszony", "Zaproszeni"))? "Przyjaciel" : "Wróg";
			
			try {

			// wzajemne relacje, więc Przyjaciele i wrogowie
			if($relacja == "Przyjaciel" || $relacja == "Wrog")
				$stmt = $this -> pdo -> prepare('
								SELECT rel2.`nr_pierwszego`
								FROM 	relacje_uzytkownikow AS rel1
								RIGHT JOIN relacje_uzytkownikow AS rel2 
								ON 	rel1.`nr_pierwszego` = rel2.`nr_drugiego`
								WHERE 	rel1.`nr_pierwszego` = rel2.`nr_drugiego` AND 
									rel1.`nr_drugiego` = rel2.`nr_pierwszego` AND 
									rel1.`nr_pierwszego` LIKE BINARY :nr_usera AND 
									rel1.nr_rodzaju = (SELECT id_relacji
										FROM rodzaje_relacji
										WHERE relacja LIKE BINARY :relacja)
								');
			else if($relacja == "Zaproszeni" || $relacja == "Zablokowani")
				$stmt = $this -> pdo -> prepare('
								SELECT rel.nr_drugiego FROM relacje_uzytkownikow AS rel
								WHERE  rel.nr_rodzaju = (SELECT id_relacji
												FROM rodzaje_relacji
												WHERE relacja LIKE BINARY :relacja) AND
									rel.nr_pierwszego LIKE BINARY :nr_usera AND 
									rel.nr_drugiego NOT IN 
										(SELECT rel2.nr_pierwszego 
										FROM 	relacje_uzytkownikow AS rel2
										WHERE   rel.nr_rodzaju = (SELECT id_relacji
												FROM rodzaje_relacji
												WHERE relacja LIKE BINARY :relacja) AND
											rel2.nr_drugiego = rel.nr_pierwszego           
									)
								');

			else if($relacja == "Zaproszony")
				$stmt = $this -> pdo -> prepare('
								SELECT rel.nr_pierwszego FROM relacje_uzytkownikow AS rel
								WHERE  rel.nr_rodzaju = (SELECT id_relacji
												FROM rodzaje_relacji
												WHERE relacja LIKE BINARY :relacja) AND
									rel.nr_drugiego LIKE BINARY :nr_usera AND 
									rel.nr_drugiego NOT IN 
										(SELECT rel2.nr_pierwszego 
										FROM 	relacje_uzytkownikow AS rel2
										WHERE   rel.nr_rodzaju = (SELECT id_relacji
												FROM rodzaje_relacji
												WHERE relacja LIKE BINARY :relacja) AND
											rel2.nr_drugiego = rel.nr_pierwszego    
										)');

		
				$stmt -> bindValue(':nr_usera', $id_uzytkownika, PDO::PARAM_STR);
				$stmt -> bindValue(':relacja', $szukanaRelacja, PDO::PARAM_STR);
				$stmt -> execute();
				
				if($stmt -> rowCount() == 0) {
					 return 0;
				}
					
				$wyn = array();	
				while($row = $stmt->fetch(PDO::FETCH_NUM))
					array_push($wyn, $row[0]);


				$stmt -> closeCursor();
				unset($stmt);

				return $wyn;

				}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
			}			
		}

	
		function ustaw_relacje($id_userow, $relacja)
		{
			try {
				if($relacja == "Usun_znajomego" || $relacja == "Odrzuc_zaproszenie") {
				$stmt = $this -> pdo -> prepare('DELETE FROM relacje_uzytkownikow WHERE (nr_pierwszego LIKE BINARY :nr_usera AND nr_drugiego LIKE BINARY :nr_drugiego) OR (nr_pierwszego LIKE BINARY :nr_drugiego AND nr_drugiego LIKE BINARY :nr_usera)');
				$stmt -> bindValue(':nr_usera', $id_userow['1st'], PDO::PARAM_STR);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> execute();

				$stmt -> closeCursor();
				unset($stmt);				
				return $relacja == "Usun_znajomego" ? 3 : 4;
				}
				

		if($relacja == "Odblokuj"){
				$stmt = $this -> pdo -> prepare('DELETE FROM relacje_uzytkownikow WHERE (nr_pierwszego LIKE BINARY :nr_usera AND nr_drugiego LIKE BINARY :nr_drugiego)');
				$stmt -> bindValue(':nr_usera', $id_userow['1st'], PDO::PARAM_STR);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> execute();

				$stmt -> closeCursor();
				unset($stmt);
				return 2;
				}				

$stmt = $this -> pdo -> prepare('SELECT id_relacji FROM rodzaje_relacji WHERE relacja LIKE BINARY :relacja');
				$stmt -> bindValue(':relacja', $relacja, PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() != 1) {
					 return -1;
				}
				else {
					$row = $stmt -> fetch();
					$rodzaj = $row['id_relacji'];
				}
				$stmt -> closeCursor();
				unset($stmt);

				$stmt = $this -> pdo -> prepare('INSERT INTO relacje_uzytkownikow VALUES (:nr_pierwszego,:nr_drugiego,:rodzaj,:data) ON DUPLICATE KEY UPDATE nr_rodzaju=:rodzaj, data_dodania=:data');
				$stmt -> bindValue(':nr_pierwszego', $id_userow['1st'], PDO::PARAM_INT);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> bindValue(':rodzaj', $rodzaj, PDO::PARAM_STR);
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

		function ileZaproszenPrzychodzacych()
		{
			try {
				$stmt = $this -> pdo -> prepare('
					SELECT rel.nr_pierwszego FROM relacje_uzytkownikow AS rel
					WHERE  rel.nr_rodzaju = (SELECT id_relacji
									FROM rodzaje_relacji
									WHERE relacja LIKE BINARY :relacja) AND
						rel.nr_drugiego LIKE BINARY :nr_usera AND 
						rel.nr_drugiego NOT IN 
							(SELECT rel2.nr_pierwszego 
							FROM 	relacje_uzytkownikow AS rel2
							WHERE   rel.nr_rodzaju = (SELECT id_relacji
									FROM rodzaje_relacji
									WHERE relacja LIKE BINARY :relacja) AND
								rel2.nr_drugiego = rel.nr_pierwszego    
							)');

		
				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_STR);
				$stmt -> bindValue(':relacja', "Przyjaciel", PDO::PARAM_STR);
				$stmt -> execute();
				
				$res = $stmt -> rowCount();
					
				$stmt -> closeCursor();
				unset($stmt);

				return $res;

				}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
			}			
		}

	}
?>
