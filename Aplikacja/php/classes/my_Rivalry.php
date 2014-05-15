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

		function edit($formPack) {
			$start = $formPack['rywStart_date'].' '.$formPack['rywStart_time'];
			$end = $formPack['rywStop_date'].' '.$formPack['rywStop_time'];
			
			try {
					$stmt = $this -> pdo -> prepare('UPDATE rywalizacje SET nazwa_rywalizacji = :nazwa, opis_rywalizacji = :opis, nr_sportu = :sport, data_startu = :start, data_konca = :koniec WHERE id_rywalizacji LIKE BINARY :rId');
					$stmt -> bindValue(':nazwa', $formPack['rywName'], PDO::PARAM_STR);
					$stmt -> bindValue(':opis', $formPack['rywInfo'], PDO::PARAM_STR);
					$stmt -> bindValue(':sport', $formPack['rywSport'], PDO::PARAM_INT);
					$stmt -> bindValue(':start', $start, PDO::PARAM_STR);
					$stmt -> bindValue(':koniec', $end, PDO::PARAM_STR);
					$stmt -> bindValue(':rId', $formPack['rywEdit_id'], PDO::PARAM_STR);

					$count = $stmt -> execute();
						
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo $e;
				return 0;
			}
			if ($count != 1) {
				return 0;
			}
			else
				return 1;

		}

		function showAll() {
				try {
					$stmt = $this -> pdo -> prepare('SELECT id_rywalizacji, nazwa_rywalizacji FROM rywalizacje');
					$stmt -> execute();

					$result = $stmt -> fetchAll();
					$stmt -> closeCursor();
					
					unset($stmt);
					return $result;
				}
				catch(PDOException $e) {
					return 0;
				}

		}

		function show($rId) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT * FROM rywalizacje WHERE id_rywalizacji LIKE BINARY :rId');
					$stmt -> bindValue(':rId', $rId, PDO::PARAM_INT);
					$stmt -> execute();

					$row = $stmt -> fetch();
					$stmt -> closeCursor();
					
					unset($stmt);
					return $row;
				}
				catch(PDOException $e) {
					return 0;
				}

		}

		function delete($rivId, $usrPol) {

			if ($usrPol < 3) {
				try {
					$stmt = $this -> pdo -> prepare('DELETE FROM rywalizacje WHERE id_rywalizacji = :rivId');
					$stmt -> bindValue(':rivId', $rivId, PDO::PARAM_INT);
					$count = $stmt -> execute();
				
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					return 0;
				}

				if ($count == 1)
					return $rivId;
				else
					return 0;

			}
			else
				return 0;
		}
	}
?>
