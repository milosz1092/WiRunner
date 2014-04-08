<?php
	include('php/top.php');
?>
		<article>
<?php
		
		if($_SESSION['WiRunner_log_id'] == 0) {	
?>
			<section>
				<div class="left_part"  style="width:500px;margin-right:70px;">
				<?php
					if (isset($_GET['msg']) && $_GET['msg'] == 'justReg') {

						echo '<div class="ok_msg">Rejestracja zakończona powodzeniem!<br /><span style="font-size:13px;font-style:italic;">Wysłaliśmy link aktywacyjny, wiadomość może trafić do spamu.</span></div>';

					}

					// akcja przy uzyciu linku aktywacyjnego (przy gosciu)
					if(isset($_GET['action']) && $_GET['action'] == 'accountActiv' && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
						if ($my_userAction->activation(array('code' => $_GET['code'], 'mail' => $_GET['mail'])))
							echo '<div class="ok_msg">Twoje konto zostało aktywowane!</div>';
						else
							echo '<div class="wrong_msg">Błąd podczas aktywacji konta!</div>';
					}
				?>


					<header class="entry-header hr_bor">
						<h1 class="entry-title">Logowanie</h1>
					</header>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
						<ul class="form_field">
							<li>
								<label for="log_email">E-mail</label>
								<input type="email" id="log_email" name="log_email" required="required" />
							</li>
							<li>
								<label for="log_haslo">Hasło</label>
								<input type="password" id="log_haslo" name="log_haslo" required="required" />
							</li>
						</ul>
						<ul>
							<li>
								Jeżeli nie posiadasz konta, <a href="register.php">zarejestruj się</a>.
							</li>
							<li style="margin-top:20px;">
								<input type="submit" name="log_send" value="Zaloguj się" /><input type="reset" value="Wyczyść" />
							</li>
							<li id="passReset" style="color:grey;margin-top:5px;display:none;">
								Nie pamiętasz hasła? <a id="resetLink" style="color:grey;" href="login.php?action=passReset&email=default@domena.pl">Zresetuj je</a>.
							</li>
						</ul>
					</form>
					<script>
						$("#log_email").focus();
					</script>
				</div>
				<div class="right_part">
			<?php
					if(isset($_POST['log_send'])) {
						$my_userAction->login(array('email' => $_POST['log_email'], 'haslo' => $_POST['log_haslo']));
					}
					else if(isset($_GET['action']) && $_GET['action'] == 'passReset') {
						if(!my_validDate::email(array($_GET['email'])))
							$bledy[] = 'Podano niepoprawny adres e-mail';

						if(isset($bledy) && count($bledy) > 0)
							my_simpleMsg::show('Nie można zresetować hasła!', $bledy, 0);
						else
							header('Location: passreset.php?email='.$_GET['email']);
					}
			?>
				</div>
			</section>

<?php
		}
		else {
			// przekierowanie: aktywacja konta z linku w podstronie konto.php
			if(isset($_GET['action']) && $_GET['action'] == 'accountActiv' && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail']))
				header('Location: konto.php?action=accountActiv&code='.$_GET['code']);
			else
				header("Location: konto.php");
		}
?>
		</article>
<?php
	include('php/bottom.php');
?>
