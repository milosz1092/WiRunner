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

		function showAll($typRywalizacji = NULL) {
						//	0 => Wszystkie
						//	1 => Trwające
						//	2 => Zakończone
						//	3 => Nierozpoczęte
				if(empty($typRywalizacji)) $typRywalizacji = 0;

				try {
					$data = date("Y-m-d H:i:s");
					switch($typRywalizacji)
					{
						case 0:
							$stmt = $this -> pdo -> prepare('SELECT id_rywalizacji, nazwa_rywalizacji FROM rywalizacje');
							break;
						case 1:
							$stmt = $this -> pdo -> prepare('SELECT id_rywalizacji, nazwa_rywalizacji FROM rywalizacje WHERE "'.$data.'" > data_startu AND "'.$data.'" < data_konca');
							break;
						case 2:
							$stmt = $this -> pdo -> prepare('SELECT id_rywalizacji, nazwa_rywalizacji FROM rywalizacje WHERE "'.$data.'" > data_konca');
							break;
						case 3:
							$stmt = $this -> pdo -> prepare('SELECT id_rywalizacji, nazwa_rywalizacji FROM rywalizacje WHERE "'.$data.'" < data_startu');
							break;
					}

					$stmt -> execute();

					$result = $stmt -> fetchAll();
					$stmt -> closeCursor();
					
					unset($stmt);
					return $result;
				}
				catch(PDOException $e) {
					return -2;
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

		function czyUzytkownikJestZapisany($rId) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT COUNT(*) FROM zgloszenia_do_rywalizacji WHERE nr_rywalizacji LIKE BINARY :rId AND nr_usera LIKE BINARY :nr_usera');
					$stmt -> bindValue(':rId', $rId, PDO::PARAM_INT);
					$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
					$stmt -> execute();

				$wyn = $stmt -> fetch();

				$stmt -> closeCursor();
				unset($stmt);
				return $wyn[0];
				}
				catch(PDOException $e) {
					return 0;
				}
		}

		function ileUczestnikow($rId) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT count(*) FROM zgloszenia_do_rywalizacji WHERE nr_rywalizacji LIKE BINARY :rId');
				$stmt -> bindValue(':rId', $rId, PDO::PARAM_INT);
				$stmt -> execute();

				$wyn = $stmt -> fetch();

				$stmt -> closeCursor();
				unset($stmt);
				return $wyn[0];
			}
			catch(PDOException $e) {
				return 0;
			}
		}

		function join($rId) {
			if($this->czyUzytkownikJestZapisany($rId)) return -1;	// użytkownik jest już zapisany

			$dane = $this->show($rId);

			$datetime1 = new DateTime("now");
			$datetime2 = new DateTime($dane['data_konca']);
			$interval = $datetime1->diff($datetime2);
			if($interval->format('%R%a') < 0) return -2;	// rywalizacja się już zakończyła

			try {
				$stmt = $this -> pdo -> prepare('INSERT INTO zgloszenia_do_rywalizacji VALUES (:rId,:nr_usera,:data)');
				$stmt -> bindValue(':rId', $rId, PDO::PARAM_INT);
				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
				$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt -> execute();
				
				$stmt -> closeCursor();
				unset($stmt);
				return 1;
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO</p>';
				return -3;
			}
		}

		function leave($rId) {
			if($this->czyUzytkownikJestZapisany($rId) == 0) return -1;
			$dane = $this->show($rId);

			$datetime1 = new DateTime("now");
			$datetime2 = new DateTime($dane['data_konca']);
			$interval = $datetime1->diff($datetime2);
			if($interval->format('%R%a') < 0) return -2;	// rywalizacja się już zakończyła
			
			try {
			$stmt = $this -> pdo -> prepare('DELETE FROM zgloszenia_do_rywalizacji WHERE nr_rywalizacji LIKE BINARY :rId AND nr_usera LIKE BINARY :nr_usera');
				$stmt -> bindValue(':rId', $rId, PDO::PARAM_INT);
				$stmt -> bindValue(':nr_usera', $_SESSION['WiRunner_log_id'], PDO::PARAM_INT);
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
		
		function ranking($rId, $start = NULL, $ile = NULL) {
			$dane = $this->show($rId);

			try {


				$stmt = $this -> pdo -> prepare('SELECT imie, nazwisko, email, nr_usera, SUM(dystans), COUNT(*) 
									FROM rywalizacje 
									INNER JOIN zgloszenia_do_rywalizacji
									ON nr_rywalizacji = id_rywalizacji 

									INNER JOIN uzytkownicy
									ON nr_usera = id_uzytkownika

									INNER JOIN aktywnosci
									ON nr_usera = nr_uzytkownika AND aktywnosci.nr_sportu = rywalizacje.nr_sportu 

									WHERE id_rywalizacji = '.$rId.' AND aktywnosci.data_dodania > data_startu  AND aktywnosci.data_dodania > data_startu AND aktywnosci.data_dodania < data_konca
									GROUP BY nr_usera
									ORDER BY SUM(dystans) DESC');

				$stmt -> execute();
				$row = $stmt -> fetchAll((PDO::FETCH_ASSOC));

				$stmt -> closeCursor();
				unset($stmt);
				return $row;
				}
				catch(PDOException $e) {
					return 0;
				}
		}


	}
?>
