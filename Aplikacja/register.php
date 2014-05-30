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
				if (isset($_GET['msg']) && $_GET['msg'] == 'justDelAcount') {

					echo '<div class="ok_msg">Twoje konto zostało usunięte!<br /><span style="font-size:13px;font-style:italic;">Wielka szkoda, że nas opuszczasz...</span></div>';

				}
?>
					<header class="entry-header">
						<h1 class="entry-title">Rejestracja</h1>
					</header>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
						<ul class="form_field">
							<li>
								<label for="reg_mail">E-mail</label>
								<input type="email" id="reg_mail" name="reg_mail" required="required" />
							</li>
							<li>
								<label for="reg_haslo">Hasło</label>
								<input type="password" id="reg_haslo" name="reg_haslo" required="required" />
							</li>
							<li>
								<label for="reg_eqhaslo">Powtórz hasło</label>
								<input type="password" id="reg_eqhaslo" name="reg_eqhaslo" required="required" />
							</li>
							<li>
								<label>Płeć</label>
								<input style="display:inline-block;width:20px;" id="reg_plec_k" type="radio" name="reg_plec" value="k" /><label style="display:inline-block;width:50px;cursor:pointer;margin-right:15px" for="reg_plec_k">kobieta</label>
								<input style="display:inline-block;width:20px;" id="reg_plec_m" type="radio" name="reg_plec" value="m" /><label style="display:inline-block;width:76px;cursor:pointer" for="reg_plec_m">mężczyzna</label>
							</li>
						</ul>
						<ul class="form_chk">
							<li>
								<input type="checkbox" id="reg_zgoda" name="reg_zgoda[]" required="required" />
								<label for="reg_zgoda">Akceptuję <a href="regulamin.php">regulamin</a> serwisu.</label>
							</li>
						</ul>
						<ul>
							<li>
								Jeżeli posiadasz już konto, <a href="login.php">zaloguj się</a>.
							</li>
							<li style="margin-top:20px;">
								<input type="submit" name="reg_send" value="Zarejestruj się" /><input type="reset" value="Wyczyść" />
							</li>
						</ul>
					</form>
					<script>
						$("#reg_mail").focus();
					</script>
				</div>
				<div class="right_part" style="width:350px;">
			<?php
					if(isset($_POST['reg_send'])) {
						if(!isset($_POST['reg_zgoda']))
							$_POST['reg_zgoda'] = 0;
							
						if(!isset($_POST['reg_plec']))
							$_POST['reg_plec'] = 0;
							
						$my_userAction->register(array('email' => $_POST['reg_mail'], 'haslo' => $_POST['reg_haslo'], 'eqhaslo' => $_POST['reg_eqhaslo'], 'plec' => $_POST['reg_plec'], 'zgoda' => $_POST['reg_zgoda']));	
					}
			?>
				</div>
			</section>
<?php
		}
		else
			header("Location: konto.php");
?>
		</article>
<?php
	include('php/bottom.php');
?>
