<?php
	final class my_usersRelations extends my_connDb {
		protected $pdo;
	
		function zwroc_typ($id_userow)
		{
			// Zwraca albo nazwę relacji, albo 0 w przypadku braku.
			// Przyjeliśmy, że użytkownicy są w stosunku do siebie zawsze w tej samej relacji i nie wymagają one potwierdzenia dwórch stron.
			// W tabeli nr_pierwszego musi być zawsze mniejszy od nr_drugiego.
			// Rodzaje relacji muszą zostać dodane do bazy, wymagane relacje to:
			// Przyjaciel, Wróg, Rodzina 

			$id_userow['1st'] > $id_userow['2nd'] ? list($id_userow['1st'],$id_userow['2nd']) = array($id_userow['2nd'], $id_userow['1st']) : ""; 
			
			
			try {
				$stmt = $this -> pdo -> prepare('SELECT relacja FROM relacje_uzytkownikow, rodzaje_relacji WHERE id_relacji LIKE BINARY  nr_rodzaju AND nr_pierwszego LIKE BINARY nr_pierwszego AND nr_drugiego LIKE BINARY nr_drugiego');
				$stmt -> bindValue(':nr_pierwszego', $id_userow['1st'], PDO::PARAM_STR);
				$stmt -> bindValue(':nr_drugiego', $id_userow['2nd'], PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() == 1) {
					$row = $stmt -> fetch();

				$stmt -> closeCursor();
				unset($stmt);

					return $row['relacja'];
				}
				else {
				$stmt -> closeCursor();
				unset($stmt);
					return 0; // brak relacji
				}
				

				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
				}
		}

		

		function znajdz_userow_w_relacji($uid, $relacja)
		{
			// Zwraca w tablicy id użytkowników spełniających podane kryterium.

			try {
				$stmt = $this -> pdo -> prepare('SELECT nr_pierwszego, nr_drugiego FROM relacje_uzytkownikow, rodzaje_relacji WHERE relacja LIKE BINARY :relacja AND (nr_pierwszego LIKE BINARY :nr_usera OR nr_drugiego LIKE BINARY :nr_usera)');
		
				$stmt -> bindValue(':nr_usera', $uid, PDO::PARAM_STR);
				$stmt -> bindValue(':relacja', $relacja, PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() == 0) {
					return 0;
				}
				else {
					$wyn = array();
						
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							array_push($wyn, $row['nr_pierwszego'] == $uid ? $row['nr_drugiego']: $row['nr_pierwszego']);
						}

					return $wyn;
				}
				$stmt -> closeCursor();
				unset($stmt);
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
				}			
		}

	
		function ustaw_relacje($id_userow, $relacja)
		{
			$id_userow['1st'] > $id_userow['2nd'] ? list($id_userow['1st'],$id_userow['2nd']) = array($id_userow['2nd'], $id_userow['1st']) : "";
			try {
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

	}
?>
