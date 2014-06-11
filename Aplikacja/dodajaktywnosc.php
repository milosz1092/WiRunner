<?php
	include('php/top.php');
?>
		<article>
			<section>
			<?php
				echo '<header class="entry-header">
						<h1 class="entry-title">Dodaj aktywność</h1>
				</header>';

				if(isset($_POST['dodajAktywnosc'])) {
					$resDodawania = $my_activities->dodajAktywnosc(
								array(
									'nazwa_treningu' => $_POST['nazwa_treningu'],
								      	'opis' => $_POST['opis'],
								     	'tempo' => $_POST['tempo'],
									'dystans' => $_POST['dystans'],
									'sport_id' => $_POST['sport_id'],
									'track_id' => $_POST['track_id'],
									'data_treningu' => $_POST['data_treningu'],
									'prywatnosc' => $_POST['prywatnosc']
									));

					if($resDodawania == -1) {
								$my_activities->formularzDodawania($my_simpleDbCheck->getSports(),
									$my_userAction->getTracks($_SESSION['WiRunner_log_id']),
									array(
									'nazwa_treningu' => $_POST['nazwa_treningu'],
								      	'opis' => $_POST['opis'],
								     	'tempo' => $_POST['tempo'],
									'dystans' => $_POST['dystans'],
									'sport_id' => $_POST['sport_id'],
									'track_id' => $_POST['track_id'],
									'data_treningu' => $_POST['data_treningu'],
									'prywatnosc' => $_POST['prywatnosc']
									));
}

					elseif($resDodawania == 1) {
						echo '<div class="ok_msg">Aktywność pomyślnie dodana!</div>';
						$my_activities->formularzDodawania($my_simpleDbCheck->getSports(),
									$my_userAction->getTracks($_SESSION['WiRunner_log_id']));
					}
				}
				
				else
				{
					$res = $my_activities->formularzDodawania($my_simpleDbCheck->getSports(), $my_userAction->getTracks($_SESSION['WiRunner_log_id']));
					if($res == -2) echo "Brak sportów do wybrania, błąd!";
				}
			?>
				
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
