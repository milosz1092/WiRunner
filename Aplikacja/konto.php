<?php
	include('php/top.php');

	
	if(isset($_GET['action']))
	switch($_GET['action'])
	{
		case 'accountActiv':
			// akcja przy uzyciu linku aktywacyjnego (przy zalogowanym uzytkowniku)
			if(isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
				if ($my_userAction->activation(array('code' => $_GET['code'], 'mail' => $_GET['mail'])))
					echo '<div class="ok_msg">Twoje konto zostało aktywowane!</div>';
				else
					echo '<div class="wrong_msg">Błąd podczas aktywacji konta!</div>';
			}
		break;
		case 'join':
			// użytkownik przyłączył do rywalizacji
			if(empty($_GET['rId']) || !intval($_GET['rId'])) break;
			$res = $my_Rivalry->join($_GET['rId']);
			
			switch($res)
			{
				case -3: echo '<div class="wrong_msg">Błąd podczas przyłączania do rywalizacji!</div>';		break;
				case -2: echo '<div class="wrong_msg">Rywalizacja się już zakończyła!</div>';			break;
				case -1: echo '<div class="wrong_msg">Bierzesz już udział w tej rywalizacji!</div>';     	break;
				case 1:  echo '<div class="ok_msg">Zostałeś pomyślnie zapisany do rywalizacji!</div>';		break;
			};
		break;

		case 'leave':
			// użytkownik zrezygnował z rywalizacji
			if(empty($_GET['rId']) || !intval($_GET['rId'])) break;
			$res = $my_Rivalry->leave($_GET['rId']);
			
			switch($res)
			{
				case -2: echo '<div class="wrong_msg">Rywalizacja się już zakończyła!</div>';			break;
				case -1: echo '<div class="wrong_msg">Nie jesteś zapisany do rywalizacji!</div>';     		break;
				case 0:  echo '<div class="wrong_msg">Nastąpił błąd przy rezygnacji z rywalizacji!</div>';	break;
				case 1:  echo '<div class="ok_msg">Zostałeś pomyślnie wypisany z rywalizacji!</div>';		break;
			};

		break;
		
	}	

// sprawdzenie, czy współrzędne nie są już czasem ustawione;
if(!$my_userAction->get_coordinates(1))
	echo '<a href="./wspolrzedne.php">Ustaw swoje współrzędne na mapie!</a><br/>';



?>
<div id="big_contener">
	<div id="left_contener">
		<div class="left_menu">
			<h3>Moje konto</h3>
			<ul class="subLinks">
<?php
				foreach ($my_siteTitle->konto_links() as $link => $pack) {
					echo '<li><a ';
					if (isset($_GET['subPage']) && $link == $_GET['subPage'])
						echo 'class="act" ';
					echo 'href="'.my_getFilename::normal().'?subPage='.$link.'">'.$pack[0].'</a> '.(($link=="zaproszenia")?'('.$my_usersRelations->ileZaproszenPrzychodzacych().')':'').'</li>';

					if ($pack[1] != NULL) {
						echo '<ul class="actionLinks">';
						foreach ($pack[1] as $actionLink => $actionName) {
							echo '<li><a ';
							if (isset($_GET['action']) && $actionLink == $_GET['action'])
								echo 'class="act" ';
							echo 'href="'.my_getFilename::normal().'?subPage='.$link.'&action='.$actionLink.'">'.$actionName.'</a></li>';
						}
						echo '</ul>';
					}
				}
?>
			</ul>
		</div>
	</div>
	<div id="right_contener">
<?php
		if (isset($_GET['subPage'])) {
			switch($_GET['subPage']) {
				case 'edytujprofil':
					if(isset($_POST['edytujDane'])) {
					$dane = array(
							'imie' => $_POST['imie'], 
							'nazwisko' => $_POST['nazwisko'], 
							'wzrost' => $_POST['wzrost'], 
							'waga' => $_POST['waga'], 
							'miejscowosc' => $_POST['miejscowosc'], 
							'motto' => $_POST['motto'], 
							'widoczny_dla_gosci' => $_POST['widoczny_dla_gosci'], 
							'data_urodzenia' => $_POST['data_urodzenia']

							);

						if($my_userAction->profile_update($dane) == -1){
							$my_userAction->profil_edit($dane);
							break;

						} else echo '<div class="ok_msg">Pomyślnie zaktualizowano dane!</div>';
					}

					$userInfo = $my_simpleDbCheck->getUserInfo($_SESSION['WiRunner_log_id']);
					$my_userAction->profil_edit($userInfo);
				break;

				case 'aktywnosci':
					$typy_sortowania = array("0" => "data malejąco",
								 "1" => "data rosnąco",
								 "2" => "dystans malejąco",
								 "3" => "dystans rosnąco");
					$sort = (isset($_GET['sort']) && isset($typy_sortowania[$_GET['sort']]))? $_GET['sort'] : 0;

					foreach($typy_sortowania as $id => $typ) {
						echo '<a href="konto.php?subPage=aktywnosci&sort='.$id.'">'.(($id == $sort)?'<i>'.$typ.'</i>':$typ).'</a> ';
					}
					
					foreach($my_activities->getUserActivities($sort) as $key => $akt) {
						if($key == 0) echo "<h2>Twoje aktywności</h2>";
						echo '<b>'.($key+1) . '</b>. <a href="aktywnosc.php?id='.$akt['id_aktywnosci'].'">'.$akt['nazwa_treningu'] .'</a> ('.$akt['nazwa_sportu'] .') - '. $akt['dystans'] . 'km ('.$akt['data_treningu'].')<br/>';
					}


				break;


				case 'trasy':
					if(isset($_GET['action']) && $_GET['action'] == "usun" && isset($_GET['id']) && intval($_GET['id'])){
							$res = $my_userAction->removeTrack($_GET['id']);
		 
							if($res == -1)
							 echo '<div class="wrong_msg">Nie masz uprawnień do usunięcia tej trasy!</div>';
							else if($res == 0)
							 echo '<div class="wrong_msg">Błąd, trasa nie usunięta!</div>';
							else if($res == 1)
							 echo '<div class="ok_msg">Trasa pomyślnie usunięta!</div>';
					} else if(isset($_GET['action']) && $_GET['action'] == "kopiuj" && isset($_GET['id']) && intval($_GET['id'])){
							
							$res = $my_userAction->copyTrack($_GET['id']);
		 
							if($res == -1)
							 echo '<div class="wrong_msg">Nie masz wymaganych uprawnień!</div>';
							else if($res == 0)
							 echo '<div class="wrong_msg">Błąd, trasa nie skopiowana!</div>';
							else if($res == 1)
							 echo '<div class="ok_msg">Trasa pomyślnie skopiowana!</div>';
					}
					echo "<h2>Twoje trasy</h2>";
					// pobranie tras użytkownika, jeżeli takowe istnieją
					if ($my_userAction->get_tracks() == 0) {
						echo '<p>Nie posiadasz zapisanych tras...</p>';
					}
					if(($przyjaciele = $my_usersRelations->znajdz_userow_w_relacji($_SESSION['WiRunner_log_id'], "Przyjaciel")) != 0){
					echo "Trasy znajomych: <br/>"; $straznik = false;
					foreach($przyjaciele as $id)
						{
							 if(($my_userAction->get_tracks($id)) != 0)
								$straznik = true;
						}
					if(!$straznik) echo "póki co brak tras..";
					}
				break;

				case 'urywalizacje':

				$wypiszNag = true;
				echo '<div style="float: left;">';
				foreach($my_Rivalry->showAll(1) as $row)  {
					if($wypiszNag) {
						echo "<h2>Aktywne rywalizacje</h2>";
						$wypiszNag = false;
					}

					$dane = $my_Rivalry->show($row['id_rywalizacji']);
					$lUczestnikow = $my_Rivalry->ileUczestnikow($row['id_rywalizacji']);

					echo '<div id="row'.$row['id_rywalizacji'].'" class="rywRow_header">';
						echo '<div class="rywTitle_header"><b>'.$row['nazwa_rywalizacji'].'</b>';
						echo '<div style="float:right; text-align: right; font-size: 12px; ">start: '.$dane['data_startu'].'<br/>koniec: '.$dane['data_konca'].'<br/>konkurencja: <b>'.$my_activities->getSport($dane['nr_sportu']).'</b><br/>zapisanych osób: <b>'.$lUczestnikow.'</b></div>';
						echo '<span style="clear: both; display: block; margin: 10px 0px 10px 0px;">opis: '.$dane['opis_rywalizacji'].'</span></div>';

				echo '<ul style="margin: 0px;padding: 0px; list-style-type: none;">';
					foreach($my_Rivalry->ranking($row['id_rywalizacji']) as $k => $ele) {
						if($k == 0) echo '&nbsp;&nbsp;&nbsp;&nbsp;imie / nazwisko <span style="float: right;"> km / l.akt</span>';
						echo '<li>'.($k+1).'. <a href="./profil.php?uid='.$ele['nr_usera'].'">';
						if(!empty($ele['imie'])) echo $ele['imie'].' '.$ele['nazwisko'];
						else	echo substr($ele['email'],8) . '...';
						echo '</a>		<span style="float: right;">'.$ele['SUM(dystans)'].' / '.$ele['COUNT(*)'].'</span>';
						echo '</li>';
					}
				echo "</ul>";


						echo '<div style="margin-top:10px;" class="rywAction_header">';

		if($my_Rivalry->czyUzytkownikJestZapisany($row['id_rywalizacji']))
			echo '<input type="button" value="Zrezygnuj z udziału" onclick="document.location.href=\'konto.php?subPage=urywalizacje&action=leave&rId='.$row['id_rywalizacji'].'\'" />';
		else
			echo '<input type="button" value="Przystąp" onclick="document.location.href=\'konto.php?subPage=urywalizacje&action=join&rId='.$row['id_rywalizacji'].'\'" />';
						echo '</div>';
					echo '</div>';
				}
				echo '</div>';

				echo '<div style="float: right;">';
				$wypiszNag = true;
				foreach($my_Rivalry->showAll(3) as $row)  {
					if($wypiszNag) {
						echo "<h2>Nadchodzące rywalizacje</h2>";
						$wypiszNag = false;
					}

					$dane = $my_Rivalry->show($row['id_rywalizacji']);
					$lUczestnikow = $my_Rivalry->ileUczestnikow($row['id_rywalizacji']);

					echo '<div id="row'.$row['id_rywalizacji'].'" class="rywRow_header">';
						echo '<div class="rywTitle_header"><b>'.$row['nazwa_rywalizacji'].'</b>';
						echo '<div style="float:right; text-align: right; font-size: 12px; ">start: '.$dane['data_startu'].'<br/>koniec: '.$dane['data_konca'].'<br/>konkurencja: <b>'.$my_activities->getSport($dane['nr_sportu']).'</b><br/>zapisanych osób: <b>'.$lUczestnikow.'</b></div>';
						echo '<span style="clear: both; display: block; margin: 10px 0px 10px 0px;">opis: '.$dane['opis_rywalizacji'].'</span></div>';

				echo '<ul style="margin: 0px;padding: 0px; list-style-type: none;">';
					foreach($my_Rivalry->ranking($row['id_rywalizacji']) as $k => $ele) {
						if($k == 0) echo '&nbsp;&nbsp;&nbsp;&nbsp;imie / nazwisko <span style="float: right;"> km / l.akt</span>';
						echo '<li>'.($k+1).'. <a href="./profil.php?uid='.$ele['nr_usera'].'">';
						if(!empty($ele['imie'])) echo $ele['imie'].' '.$ele['nazwisko'];
						else	echo substr($ele['email'],8) . '...';
						echo '</a>		<span style="float: right;">'.$ele['SUM(dystans)'].' / '.$ele['COUNT(*)'].'</span>';
						echo '</li>';
					}
				echo "</ul>";
						echo '<div style="margin-top:10px;" class="rywAction_header">';

					if($my_Rivalry->czyUzytkownikJestZapisany($row['id_rywalizacji']))
						echo '<input type="button" value="Zrezygnuj z udziału" onclick="document.location.href=\'konto.php?subPage=urywalizacje&action=leave&rId='.$row['id_rywalizacji'].'\'" />';
					else
						echo '<input type="button" value="Przystąp" onclick="document.location.href=\'konto.php?subPage=urywalizacje&action=join&rId='.$row['id_rywalizacji'].'\'" />';
						echo '</div>';
				}
				

				if(!$wypiszNag) {
					echo '</div><div>';
					$wypiszNag = true;
				}

				foreach($my_Rivalry->showAll(2) as $row)  {
					if($wypiszNag) {
						echo "<h2>Zakończone rywalizacje</h2>";
						$wypiszNag = false;
					}

					$dane = $my_Rivalry->show($row['id_rywalizacji']);
					$lUczestnikow = $my_Rivalry->ileUczestnikow($row['id_rywalizacji']);

					echo '<div id="row'.$row['id_rywalizacji'].'" class="rywRow_header">';
						echo '<div class="rywTitle_header"><b>'.$row['nazwa_rywalizacji'].'</b>';
						echo '<div style="float:right; text-align: right; font-size: 12px; ">start: '.$dane['data_startu'].'<br/>koniec: '.$dane['data_konca'].'<br/>konkurencja: <b>'.$my_activities->getSport($dane['nr_sportu']).'</b><br/>zapisanych osób: <b>'.$lUczestnikow.'</b></div>';
						echo '<span style="clear: both; display: block; margin: 10px 0px 10px 0px;">opis: '.$dane['opis_rywalizacji'].'</span></div>';

				echo '<ul style="margin: 0px;padding: 0px; list-style-type: none;">';
					foreach($my_Rivalry->ranking($row['id_rywalizacji']) as $k => $ele) {
						if($k == 0) echo '&nbsp;&nbsp;&nbsp;&nbsp;imie / nazwisko <span style="float: right;"> km / l.akt</span>';
						echo '<li>'.($k+1).'. <a href="./profil.php?uid='.$ele['nr_usera'].'">';
						if(!empty($ele['imie'])) echo $ele['imie'].' '.$ele['nazwisko'];
						else	echo substr($ele['email'],8) . '...';
						echo '</a>		<span style="float: right;">'.$ele['SUM(dystans)'].' / '.$ele['COUNT(*)'].'</span>';
						echo '</li>';
					}
				echo "</ul>";
					echo '</div>';
				}
				echo '</div>';


				break;




				case 'delacount':
					if (isset($_POST['delaco_send'])) {
						if ($my_userAction->delAcount($_POST, $_SESSION['WiRunner_log_id'])) {
							$_SESSION['WiRunner_log_id'] = 0;
							$_SESSION['WiRunner_policy'] = 0;
							$_SESSION['WiRunner_login'] = '';
							header("Location: register.php?msg=justDelAcount");
						}
					}
?>
					<header class="entry-header hr_bor">
						<h1 class="entry-title">Usuwanie konta</h1>
					</header>
					<p style="margin-bottom:30px;">Aby usunąć swoje konto musisz podać hasło.</p>
					<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off">
						<ul class="form_field">
							<li>
								<label for="delaco_haslo_cur">Aktualne hasło</label>
								<input type="password" id="delaco_haslo_cur" name="delaco_haslo_cur" required="required" />
							</li>
						</ul>
						<ul>
							<li style="margin-top:20px;">
								<input type="submit" name="delaco_send" value="Usuń konto" />
							</li>
						</ul>
					</form>
					<script>
						$("#delaco_haslo_cur").focus();
					</script>
<?php
				break;
				case 'chpass':
					if (isset($_POST['ch_haslo_send'])) {
						if ($my_userAction->passChange($_POST, $_SESSION['WiRunner_log_id'])) {
?>
							<div class="ok_msg">Twoje hasło zostało zmienione!</div>
<?php
						}
					}
?>
					<header class="entry-header hr_bor">
						<h1 class="entry-title">Zmień hasło</h1>
					</header>
					<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off">
						<ul class="form_field">
							<li>
								<label for="ch_haslo_cur">Aktualne hasło</label>
								<input type="password" id="ch_haslo_cur" name="ch_haslo_cur" required="required" />
							</li>
							<li>
								<label for="ch_haslo_new">Nowe hasło</label>
								<input type="password" id="ch_haslo_new" name="ch_haslo_new" required="required" />
							</li>
							<li>
								<label for="ch_eq_haslo_new">Powtórz hasło</label>
								<input type="password" id="ch_eq_haslo_new" name="ch_eq_haslo_new" required="required" />
							</li>
						</ul>
						<ul>
							<li style="margin-top:20px;">
								<input type="submit" name="ch_haslo_send" value="Zmień" />
							</li>
						</ul>
					</form>
					<script>
						$("#ch_haslo_cur").focus();
					</script>
<?php
				break;
				case 'poczta':
					if (isset($_GET['msg']) && $_GET['msg'] == 'justSendMsg')
						echo '<div class="ok_msg">Twoja wiadomość została wysłana!</div>';
					if (isset($_GET['action'])) {
						switch($_GET['action']) {
							case 'writeMsg':

								if (isset($_POST['send_msg'])) {
									$my_Poster->sendMsg($_POST);
								}
								else {
									$userInfo = $my_simpleDbCheck->getUserInfo($_GET['uid']);
?>
									<form id="writeMsg" name="writeMsg" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
										<ul class="form_field">
											<input type="text" name="ToUid_msg" value="<?php echo $_GET['uid']; ?>" hidden />
											<input type="text" name="FromUid_msg" value="<?php echo $_SESSION['WiRunner_log_id']; ?>" hidden />
											<li>
												<label style="width:60px;" for="to_msg">Adresat</label>
												<input style="width:300px;" type="text" id="to_msg" name="to_msg" required="required" value="<?php if ($userInfo['imie'] == '' || $userInfo['nazwisko'] == '') echo $userInfo['email']; else echo $userInfo['imie'].' '.$userInfo['nazwisko'];?>" disabled />
											</li>
											<li>
												<label style="width:60px;" for="title_msg">Tytuł</label>
												<input style="width:300px;" type="text" id="title_msg" name="title_msg" required="required" />
											</li>
											<li style="height:250px;">
												<textarea required="required" style="display:block;margin-bottom:10px;width:500px;height:200px;" name="content_msg" id="content_msg" rows="10" cols="80"></textarea>
											</li>
										</ul>
										<ul>
											<li><input style="margin-left:10px;" type="submit" name="send_msg" id="send_msg" value="Wyślij wiadomość"/></li>
										</ul>			
									</form>
									<script>
										$("#title_msg").focus();
									</script>
<?php
								}
							break;
							case 'showMsg':
								$row = $my_Poster->showMsg($_GET['msgId']);
								
								$userInfo = $my_simpleDbCheck->getUserInfo($row['nr_nadawcy']);

								if ($userInfo['imie'] == '' || $userInfo['nazwisko'] == '')
									$from = $userInfo['email'];
								else
									$from = $userInfo['imie'].' '.$userInfo['nazwisko'];
									
								echo '<div class="showMsg_windows">';
									echo '<div class="showMsg_from"><a href="profil.php?uid='.$row['nr_nadawcy'].'">'.$from.'</a></div><div class="showMsg_title">'.$row['temat'].'</div>';
									echo '<div class="showMsg_content">'.$row['tresc'].'</div>';
								echo '</div>';
								echo '<input type="button" value="Usuń" onclick="delMsg('.$row['id_wiadomosci'].', '.$_SESSION['WiRunner_log_id'].', \'show\')" />';
								echo '<input type="button" value="Odpowiedz" onclick="document.location.href=\'konto.php?subPage=poczta&action=writeMsg&uid='.$row['nr_nadawcy'].'\'" />';
							break;
						}
					} else {
						// domyslny wyglad po wejsciu do poczty
?>
						<!--<div id="rowId" class="showMsg_row">
							<div class="showMsg_from">OD:</div>
							<div class="showMsg_title">TYTUŁ:</div>
							<div class="showMsg_action"></div>
						</div>-->
						<header class="entry-header">
							<h1 class="entry-title">Skrzynka odbiorcza</h1>
						</header>
<?php	
						if (isset($_GET['msg']) && $_GET['msg'] == 'justDelMsg') {

							echo '<div class="ok_msg">Wiadomość została usunięta!</div>';

						}
						
						foreach($my_Poster->showInbox($_SESSION['WiRunner_log_id']) as $row)  {
							$userInfo = $my_simpleDbCheck->getUserInfo($row['nr_nadawcy']);

							if ($userInfo['imie'] == '' || $userInfo['nazwisko'] == '')
								$from = $userInfo['email'];
							else
								$from = $userInfo['imie'].' '.$userInfo['nazwisko'];

							echo '<div id="row'.$row['id_wiadomosci'].'" class="showMsg_header_row">';
								echo '<div class="showMsg_header_from"><a href="profil.php?uid='.$row['nr_nadawcy'].'">'.$from.'</a></div>';
								echo '<div class="showMsg_header_title"><a href="konto.php?subPage=poczta&action=showMsg&msgId='.$row['id_wiadomosci'].'">'.$row['temat'].'</a></div>';
								echo '<div class="showMsg_header_action">';
									echo '<input type="button" value="Usuń" onclick="delMsg('.$row['id_wiadomosci'].', '.$_SESSION['WiRunner_log_id'].', \'header\')" />';
									echo '<input type="button" value="Odpowiedz" onclick="document.location.href=\'konto.php?subPage=poczta&action=writeMsg&uid='.$row['nr_nadawcy'].'\'" />';
								echo '</div>';
							echo '</div>';
						}
					}
?>
				
<?php
				break;
				case 'przyjaciele':
					$przyjaciele = $my_usersRelations->znajdz_userow_w_relacji($_SESSION['WiRunner_log_id'], "Przyjaciel");
					if (!$przyjaciele) {
						echo 'Nie masz jeszcze żadnych przyjaciół!'; 
						break; 
					}
			
					echo "<ul>";				
					foreach($przyjaciele as $uid)
					{
						$user_info = $my_comments->getUserInfo($uid);
						echo '<li><a href="profil.php?uid='.$uid.'">'.((isset($user_info['imie']))? $user_info['imie'] . ' ' . $user_info['nazwisko'] : $user_info['email']) . '</a></li>';
					}
					echo "</ul>";

				break;
				case 'zaproszenia':
				$zaproszenia = $my_usersRelations->znajdz_userow_w_relacji($_SESSION['WiRunner_log_id'], "Zaproszony");
					if (!$zaproszenia) {
						echo 'Nie masz przychodzących zaproszeń!'; 
						break; 
					}
			
					echo "<ul>";				
					foreach($zaproszenia as $uid)
					{
						$user_info = $my_comments->getUserInfo($uid);
						echo '<li><a href="profil.php?uid='.$uid.'">'.((isset($user_info['imie']))? $user_info['imie'] . ' ' . $user_info['nazwisko'] : $user_info['email']) . '</a>';
								
						echo '<a href="profil.php?uid='.$uid.'&relacja=przyjaciel">Akceptuj zaproszenie</a> / <a href="profil.php?uid='.$uid.'&relacja=odrzuc_zaproszenie">Odrzuć zaproszenie</a>';
						

						echo '</li>';
					}
					echo "</ul>";
				break;	
				case 'polubione':
					if(($id_polubionych = $my_comments->idPolubionych($_SESSION['WiRunner_log_id'])) == 0)
					echo 'Brak polubień!';
					else
					{
						echo "<h2>Twoje polubione aktywności</h2>";
						echo "<ul>";
						foreach($id_polubionych as $ele)
						{
							if(($aktywnosc = $my_activities->getActivityById($ele['nr_aktywnosci'])) == 0)
								continue;
							echo '<li><a href="./aktywnosc.php?id='.$ele['nr_aktywnosci'].'">'.$aktywnosc['nazwa_treningu'] . '</a></li>';					
						}
						echo '</ul>';
					}
				break;
			}
		}
?>
	</div>
</div>
<?php
	include('php/bottom.php');
?>
