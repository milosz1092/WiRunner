<?php

	// dane wejściowe to tablica zawierająca takie indeksy jak
	// 'id_usera', 'imie', 'nazwisko', 'miejscowosc'
	// dane te niestety muszą zostać wcześniej zapisane do tymczasowego pliku users.txt, tak by można je swobodnie wczytywać
	// zarówno fraza, jak i dane są już napisane małymi literami

function wyswietl($rekord)
{
	$rekord = explode("|",$rekord);
	echo '<div class="rekord"><div class="kwadracik"></div><a href="profil.php?uid='.$rekord[0].'">' .ucfirst($rekord[1]) . " " . ucfirst($rekord[2]) . ' - ' .  ucwords(strtolower($rekord[3])) . '</a></div>';
}

if(empty($_GET['fraza'])) { echo "Znajdź swoich znajomych!"; return 0;}
	$dane = file('users.txt');
		$iloscWynikow = 0;
		$frazy = explode(" ",strtolower($_GET['fraza']));
		$podanychDanych = sizeof($frazy); if(empty($frazy[$podanychDanych-1])) $podanychDanych--;

			echo "<ul>";
			foreach($dane as $rekord)
			{
				$zgodnosc = 0;
				$ele = explode("|", strtolower($rekord));

			for($i=0; $i<$podanychDanych; $i++)
			{
				$pos = stripos($ele[1], $frazy[$i]);
					if($pos === 0)
						{ $zgodnosc++; $ele[1] = ""; }
					else
						{
							$pos = stripos($ele[2], $frazy[$i]);
							if($pos === 0)
								{ $zgodnosc++; $ele[2] = ""; }
							else	
								{
									$pos = stripos($ele[3], $frazy[$i]);
									if($pos === 0)
										{ $zgodnosc++; $ele[3] = ""; }	
								}
						}
			}


				if($podanychDanych == $zgodnosc){
					echo "<li>".wyswietl($rekord)."</li>";
					$iloscWynikow++;
				}
			}
			echo "</ul>";
			
		if($iloscWynikow == 0) 
			echo "<i>brak osób spełniających podane kryteria";


?>
