<?php
	include('php/top.php');


// Tworzymy listę ID użytkowników, których najnowsze aktywności będziemy pobierać.
// Najpierw pobieramy przyjaciół, potem dodajemy samego użytkownika, sortujemy tablicę i pobieramy wyniki.

$przyjaciele = $my_usersRelations->znajdz_userow_w_relacji($_SESSION['WiRunner_log_id'], "Przyjaciel");

if (!$przyjaciele) $przyjaciele = array();

	array_push($przyjaciele, $_SESSION['WiRunner_log_id']);
	sort($przyjaciele);
	$p = implode(",", $przyjaciele);

		$id_aktywnosci_przyjaciol = $my_activities->zwrocNajnowszeId($p);
		if(!empty($id_aktywnosci_przyjaciol)){
			echo "<h1>Najnowsze aktywności twoje i twoich znajomych</h1>";
		} else {
			echo "Jak narazie brak aktywności! Do roboty!!";
		}

		foreach($id_aktywnosci_przyjaciol as $id)
		{
			$my_activities->printActivity($id['id_aktywnosci']);
		}

	include('php/bottom.php');
?>
