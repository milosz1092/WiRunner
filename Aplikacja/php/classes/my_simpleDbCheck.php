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
	}
?>
