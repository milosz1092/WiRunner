<?php
	include('php/top.php');

	// akcja przy uzyciu linku aktywacyjnego (przy zalogowanym uzytkowniku)
	if(isset($_GET['action']) && $_GET['action'] == 'accountActiv' && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
		if ($my_userAction->activation(array('code' => $_GET['code'], 'mail' => $_GET['mail'])))
			echo '<div class="ok_msg">Twoje konto zostało aktywowane!</div>';
		else
			echo '<div class="wrong_msg">Błąd podczas aktywacji konta!</div>';
	}

// sprawdzenie, czy współrzędne nie są już czasem ustawione;
if(!$my_userAction->get_coordinates(1))
	echo '<a href="./wspolrzedne.php">Ustaw swoje współrzędne na mapie!</a>';


?>
<div id="big_contener">
	<div id="left_contener">
		<div class="left_menu">
			<h3>Moje konto</h3>
			<ul>
<?php
				foreach ($my_siteTitle->konto_links() as $link => $title) {
					echo '<li><a ';
					if (isset($_GET['subPage']) && $link == $_GET['subPage'])
						echo 'class="act" ';
					echo 'href="'.my_getFilename::normal().'?subPage='.$link.'">'.$title.'</a></li>';
				}
?>
			</ul>
		</div>
	</div>
	<div id="right_contener">
<?php
		if (isset($_GET['subPage'])) {
			switch($_GET['subPage']) {
				case 'trasy':
					// pobranie tras użytkownika, jeżeli takowe istnieją
					if ($my_userAction->get_tracks() == 0) {
						echo '<p>Nie posiadasz zapisanych tras...</p>';
					}
?>
				
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
												<input style="width:300px;" type="text" id="to_msg" name="to_msg" required="required" value="<?php echo $userInfo['imie'].' '.$userInfo['nazwisko']; ?>" disabled />
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
						}
					} else {
						// domyslny wyglad po wejsciu do poczty
						foreach($my_Poster->showInbox($_SESSION['WiRunner_log_id']) as $row)  {
							echo $row['temat'].'<br />';
						}
					}
?>
				
<?php
				break;
			}
		}
?>
	</div>
</div>
<?php
	include('php/bottom.php');
?>
