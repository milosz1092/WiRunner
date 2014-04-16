<?php
	final class my_siteTitle {
		private $titles;

		function __construct () {
			$this -> titles = array(
				'index.php'		=> 'Strona Główna',
				'konto.php'		=> 'Moje konto',
				'kontakt.php'		=> 'Kontakt',
				'register.php'		=> 'Rejestracja',
				'login.php'		=> 'Logowanie',
				'regulamin.php'		=> 'Regulamin',
				'passreset.php'		=> 'Reset hasła',
				'kalkulatortempa.php'	=> 'Kalkulator tempa',
				'wspolrzedne.php'	=> 'Ustawianie startu tworzenia trasy',
				'trasy.php'		=> 'Przeglądaj trasy',
				'wytyczanietrasy.php'	=> 'Wyznacz trasę dla swoich aktywności'
			);
		}


		function get() {
			return $this -> titles[my_getFilename::normal()];
		}
	}
?>
