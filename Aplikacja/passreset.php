<?php
	include('php/top.php');
?>
		<article>
<?php
		if($_SESSION['WiRunner_log_id'] == 0) {
			if (isset($_GET['action']) && $_GET['action'] == 'resetNow') {

				if (isset($_POST['reset_haslo_send'])) {
					$my_userAction->pass_resetNow($_POST['res_haslo_new'], $_GET['mail']);
				}
				else {
?>
					<header class="entry-header hr_bor">
						<h1 class="entry-title">Resetowanie hasła</h1>
					</header>
					<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off">
						<ul class="form_field">
							<li>
								<label for="res_haslo_new">Nowe hasło</label>
								<input type="password" id="res_haslo_new" name="res_haslo_new" required="required" />
							</li>
						</ul>
						<ul>
							<li style="margin-top:20px;">
								<input type="submit" name="reset_haslo_send" value="Zmień" />
							</li>
						</ul>
					</form>
					<script>
						$("#res_haslo_new").focus();
					</script>
<?php
				}
			}
			else {
?>
				<section>
					<div class="left_part"  style="width:500px;margin-right:70px;">
						<header class="entry-header hr_bor">
							<h1 class="entry-title">Resetowanie hasła</h1>
						</header>
						<p style="font-size:14px;">Jeżeli chcesz zmienić swoje hasło, klinkij w przycisk "RESETUJ HASŁO".<br />Na twój adres e-mail zostanie wysłany link resetujący.</p>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
							<ul>
								<li style="margin-top:20px;">
									<input type="submit" name="passreset_send" value="RESETUJ HASŁO" />
									<input type="hidden" name="passreset_mail" value="<?php if (isset($_GET['email'])) echo $_GET['email']; else if (isset($_POST['passreset_mail'])) echo $_POST['passreset_mail']?>" />
								</li>
							</ul>
						</form>
					</div>
					<div class="right_part">
					<?php
						if (isset($_POST['passreset_send'])) {
$mail = $_POST['passreset_mail'];
$link = 'http://wi.ourtrips.pl/passreset.php?action=resetNow&code='.md5($_POST['passreset_mail'].'zXdfcmKs35Dc').'&mail='.$_POST['passreset_mail'];
echo $link.'<br />';
$wiadomosc = <<<EOD
<html>
	<body>
		<h2>Reset hasła dla $mail!</h2>
		<p>Jeżeli chcesz zresetować swoje hasło, kliknij w link ponieżej:</p>
		<a href="$link">$link</a>
	</body>
</html>
EOD;
							if ($my_userAction->pass_reset($_POST['passreset_mail']))
								my_eMail::send($wiadomosc, 'wi.runner@gmail.com', $_POST['passreset_mail'], 'Reset hasła :: WiRunner', 'passreset');
						}
					?>
					</div>
				</section>

<?php
			}
		}
		else
			header("Location: konto.php");
?>
		</article>
<?php
	include('php/bottom.php');
?>
