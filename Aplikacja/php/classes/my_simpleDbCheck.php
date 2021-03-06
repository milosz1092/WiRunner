<?php
	class my_simpleDbCheck extends my_connDb {
		protected $pdo;

		function userIssetFromMail($mail) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika FROM uzytkownicy WHERE email LIKE BINARY :mail');
				$stmt -> bindValue(':mail', $mail, PDO::PARAM_STR);
				$stmt -> execute();
				if($stmt -> rowCount() == 1) {
					return 1;
				}
				else {
					return 0;
				}
				
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				return 0;
			}
		}
		
		function userIssetFromId($id) {
			try {
				$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika FROM uzytkownicy WHERE id_uzytkownika LIKE BINARY :id');

				$stmt -> bindValue(':id', $id, PDO::PARAM_INT);
				$stmt -> execute();
				if($stmt -> rowCount() == 1) {
					return 1;
				}
				else {
					return 0;
				}
				
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				return 0;
			}
		}

		function getUsersInfo() {
			try {
				$stmt = $this -> pdo -> prepare('SELECT id_uzytkownika, imie, nazwisko, miejscowosc FROM uzytkownicy WHERE imie IS NOT NULL');
				$stmt -> execute();		
				if($stmt -> rowCount() == 0) {
					return 0;
				}
				else {
					$wyn = array();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					array_push($wyn, array('id_usera'=>$row['id_uzytkownika'], 'imie'=>$row['imie'], 'nazwisko'=>$row['nazwisko'], 'miejscowosc'=>$row['miejscowosc']));
				}
				
				$stmt -> closeCursor();
				unset($stmt);
				return $wyn;
			}
			}
			catch(PDOException $e) {
				echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
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

		function getSports() {
			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM sporty');
				$stmt -> execute();

				$row = $stmt -> fetchAll();
				
				$stmt -> closeCursor();
				unset($stmt);
				
				return $row;
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
			}
		}

		function getUsersList() {
			try {
				$stmt = $this -> pdo -> prepare('SELECT * FROM uzytkownicy');
				$stmt -> execute();

				$row = $stmt -> fetchAll();;
				
				$stmt -> closeCursor();
				unset($stmt);
				
				return $row;
			}
			catch(PDOException $e) {
				//echo '<p>Wystąpił błąd biblioteki PDO1</p>';
				//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
			}
		}

		function getNumberOfUsersLogonSince($days) {
			try {
				$data  = date("Y-m-d H:i:s", time()- 86400*$days);
				$stmt = $this -> pdo -> prepare('SELECT COUNT(*) FROM uzytkownicy WHERE "'.$data.'" < ostatnie_logowanie');
				$stmt -> execute();

				$result = $stmt -> fetch();
				$stmt -> closeCursor();
			
				unset($stmt);
				return $result[0];
			}
			catch(PDOException $e) {
				return -1;
			}		

		}
}
?>
