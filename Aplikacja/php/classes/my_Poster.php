<?php
	class my_Poster extends my_connDb {
		protected $pdo;

		function sendMsg($msgInfo) {
			$from = $msgInfo['FromUid_msg'];
			$to = $msgInfo['ToUid_msg'];
			$title = $msgInfo['title_msg'];
			$content = $msgInfo['content_msg'];

			try {
					$stmt = $this -> pdo -> prepare('INSERT INTO wiadomosci(nr_nadawcy, nr_adresata, temat, tresc, data_nadania) VALUES(:from, :to, :title, :content, :date)');
					$stmt -> bindValue(':from', $from, PDO::PARAM_INT);
					$stmt -> bindValue(':to', $to, PDO::PARAM_INT);
					$stmt -> bindValue(':title', $title, PDO::PARAM_STR);
					$stmt -> bindValue(':content', $content, PDO::PARAM_STR);
					$stmt -> bindValue(':date', date("Y-m-d H:i:s"), PDO::PARAM_STR);

					$count = $stmt -> execute();
					if ($count != 1)
						$bledy[] = 'Problem związany z bazą danych';
						
				$stmt -> closeCursor();
				unset($stmt);
			}
			catch(PDOException $e) {
				$bledy[] = 'Problem związany z bazą danych';
			}
			if (isset($bledy)) {
				my_simpleMsg::show('Błedy podczas wysyłania wiadomości!', $bledy, 0);
				return 0;
			}
			else {
				header("Location: konto.php?subPage=poczta&msg=justSendMsg");
			}

		}

		function showInbox($uid) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT * FROM wiadomosci WHERE nr_adresata LIKE BINARY :userId');
					$stmt -> bindValue(':userId', $uid, PDO::PARAM_INT);
					$stmt -> execute();

					$result = $stmt -> fetchAll();
					$stmt -> closeCursor();
					
					unset($stmt);
					return $result;
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				}

		}

		function showMsg($msgId) {
				try {
					$stmt = $this -> pdo -> prepare('SELECT * FROM wiadomosci WHERE id_wiadomosci LIKE BINARY :msgId');
					$stmt -> bindValue(':msgId', $msgId, PDO::PARAM_INT);
					$stmt -> execute();

					$row = $stmt -> fetch();

					if ($row['data_przeczytania'] == NULL) {
						// data odebrania wiadomosci
						$stmt = $this -> pdo -> prepare('UPDATE wiadomosci SET data_przeczytania = :data WHERE id_wiadomosci LIKE BINARY :msgId');
						$stmt -> bindValue(':data', date("Y-m-d H:i:s"), PDO::PARAM_STR);
						$stmt -> bindValue(':msgId', $msgId, PDO::PARAM_INT);
						$stmt -> execute();
					}
					
					$stmt -> closeCursor();
					unset($stmt);
					
					return $row;
				}
				catch(PDOException $e) {
					echo '<p>Wystąpił błąd biblioteki PDO1</p>';
					//echo '<p>Wystąpił błąd biblioteki PDO: ' . $e -> getMessage().'</p>';
				}

		}

		function delMsg($msgId, $usrId) {
				try {
					$stmt = $this -> pdo -> prepare('DELETE FROM wiadomosci WHERE id_wiadomosci = :msgId AND (nr_nadawcy = :usrId OR nr_adresata = :usrId)');
					$stmt -> bindValue(':msgId', $msgId, PDO::PARAM_INT);
					$stmt -> bindValue(':usrId', $usrId, PDO::PARAM_INT);
					$count = $stmt -> execute();
					
					$stmt -> closeCursor();
					unset($stmt);
				}
				catch(PDOException $e) {
					return 0;
				}

				if ($count == 1)
					return $msgId;
				else
					return 0;

		}
	}
?>
