<?php
	include('php/top.php');
?>
		<article>
<?php
		if($_SESSION['WiRunner_log_id'] == 0) {
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
$link = 'http://wi.ourtrips.pl/passreset.php?action=resetNow&code='.md5($_POST['passreset_mail']).'&mail='.$_POST['passreset_mail'];
$wiadomosc = <<<EOD
<html>
	<body>
		<h2>Reset hasła dla $mail!</h2>
		<p>Jeżeli chcesz zresetować swoje hasło, kliknij w link ponieżej:</p>
		<a href="$link">$link</a>
	</body>
</html>
EOD;
						my_eMail::send($wiadomosc, 'wi.runner@gmail.com', $_POST['passreset_mail'], 'Reset hasła :: WiRunner', 'passreset');
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
