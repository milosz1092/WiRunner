<?php
	class my_Rivalry extends my_connDb {
		protected $pdo;

		function add($formPack) {
			$start = $formPack['rywStart_date'].' '.$formPack['rywStart_time'];
			$end = $formPack['rywStop_date'].' '.$formPack['rywStop_time'];
			
			try {
					$stmt = $this -> pdo -> prepare('INSERT INTO rywalizacje(nazwa_rywalizacji, opis_rywalizacji, nr_sportu, data_startu, data_konca, data_dodania) VALUES(:nazwa, :opis, :sport, :start, :koniec, :dodanie)');
					$stmt -> bindValue(':nazwa', $formPack['rywName'], PDO::PARAM_STR);
					$stmt -> bindValue(':opis', $formPack['rywInfo'], PDO::PARAM_STR);
					$stmt -> bindValue(':sport', $formPack['rywSport'], PDO::PARAM_INT);
					$stmt -> bindValue(':start', $start, PDO::PARAM_STR);
					$stmt -> bindValue(':koniec', $end, PDO::PARAM_STR);
					$stmt -> bindValue(':dodanie', date("Y-m-d H:i:s"), PDO::PARAM_STR);


					$count = $stmt -> execute();
						
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				return 0;
			}
			if (isset($bledy) || $count != 1) {
				return 0;
			}
			else
				return 1;

		}
	}
?>
