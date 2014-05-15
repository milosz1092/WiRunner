<?php
	ob_start();
	require('php/sessions.php');
	require('php/classesSet.php');
	
	if(!isset($_SESSION['WiRunner_log_id']))
		$_SESSION['WiRunner_log_id'] = 0;

	if(!isset($_SESSION['WiRunner_policy']))
		$_SESSION['WiRunner_policy'] = 0;

	if(!isset($_SESSION['WiRunner_login']))
		$_SESSION['WiRunner_login'] = '';

	if (($_SESSION['WiRunner_log_id'] == 0 && my_getFilename::normal() != 'login.php') && my_getFilename::normal() != 'register.php' && my_getFilename::normal() != 'regulamin.php' && my_getFilename::normal() != 'kontakt.php' && my_getFilename::normal() != 'passreset.php' && my_getFilename::normal() != 'wytyczanietrasy.php') {
		header("Location: login.php");
	}

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $my_siteTitle -> get(); ?> - WiRunner .:: Zaplanuj swój trening! ::.</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<meta name="robots" content="<?php  if (my_getFilename::normal() != 'konto.php') echo 'index, follow'; else echo 'noindex, nofollow'; ?>" />
		<link rel="stylesheet" href="style/default.css" type="text/css" />
		<link rel="shortcut icon" href="img/web/ikona.jpg" />
		<script src="js/jquery-2.0.0.min.js"></script>
		<script src="js/default.js"></script>
	<?php  if (my_getFilename::normal() != 'kalkulatorTempa.php') echo '<script src="js/kalkulatorTempa.js"></script>'; ?>
	</head>
	<body>
		<div id="paper">
			<header>
				<!--<img class="site-logo" src="" alt="logo" />-->
				<div class="navi">
					<div class="navi-l">
						<h1 class="site-title">
							<a href=".">WiRunner</a>
						</h1>
						<h2 class="site-description">Zaplanuj swój trening.</h2>
					</div>
					<div class="navi-r">
						<div id="logUser_info">
						<?php
							if ($_SESSION['WiRunner_log_id'] == 0) {
								echo 'Witaj nieznajomy!';
							}
							else {
								$temp = $my_simpleDbCheck->getUserInfo($_SESSION['WiRunner_log_id']);
								if(empty($temp['imie'])){
									echo 'Witaj '.$_SESSION['WiRunner_login'].'!';
									echo '<br/><a href="./konto.php?subPage=edytujprofil"><span style="color:red; font-style: italic;">Uzupełnij swoje dane!</span></a><br/>';
								}
								else
									echo 'Jesteś zalogowany jako '.$temp['imie'].' '.$temp['nazwisko'].'!';
								unset($temp);
							}
						?>
						</div>
					</div>
				</div>
			</header>
			<nav>
				<div class="main-menu-container">
					<?php 
						$my_mainMenu -> drukuj($_SESSION['WiRunner_log_id']);
					?>
				</div>
			</nav>
