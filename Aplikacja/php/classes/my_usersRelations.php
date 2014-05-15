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

	}
?>
