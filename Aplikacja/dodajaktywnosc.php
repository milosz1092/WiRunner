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
									'data_treningu' => $_POST['data_treningu']
									));

					if($resDodawania == -1) $my_activities->formularzDodawania($my_simpleDbCheck->getSports(), 
									array(
									'nazwa_treningu' => $_POST['nazwa_treningu'],
								      	'opis' => $_POST['opis'],
								     	'tempo' => $_POST['tempo'],
									'dystans' => $_POST['dystans'],
									'sport_id' => $_POST['sport_id'],
									'data_treningu' => $_POST['data_treningu']
									));

					elseif($resDodawania == 1) {
						echo '<div class="ok_msg">Aktywność pomyślnie dodana!</div>';
						$my_activities->formularzDodawania($my_simpleDbCheck->getSports());
					}
				}
				
				else
				{
					$res = $my_activities->formularzDodawania($my_simpleDbCheck->getSports());
					if($res == -1) echo "Brak sportów do wybrania, błąd!";
				}
			?>
				
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
