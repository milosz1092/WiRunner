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
				?>
						<div class="ok_msg">Rejestracja zakończona powodzeniem!</div>
				<?php
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
							<li style="color:grey;margin-top:5px;display:none;">
								Nie pamiętasz hasła? <a style="color:grey;" href="passreset.php">Zresetuj je</a>.
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
