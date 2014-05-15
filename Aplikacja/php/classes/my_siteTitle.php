<?php
	final class my_siteTitle {
		private $titles;
		private $konto_links;
		private $admin_links;

		function __construct () {
			$this -> titles = array(
				'index.php'				=> 'Strona Główna',
				'konto.php'				=> 'Moje konto',
				'profil.php'			=> 'Profil',
				'admin.php'				=> 'Administracja',
				'kontakt.php'			=> 'Kontakt',
				'register.php'			=> 'Rejestracja',
				'login.php'				=> 'Logowanie',
				'regulamin.php'			=> 'Regulamin',
				'passreset.php'			=> 'Reset hasła',
				'kalkulatortempa.php'	=> 'Kalkulator tempa',
				'szukaj.php'			=> 'Wyszukiwarka',
				'wspolrzedne.php'		=> 'Ustawianie startu tworzenia trasy',
				'trasy.php'				=> 'Przeglądaj trasy',
				'wytyczanietrasy.php'	=> 'Wyznacz trasę dla swoich aktywności'
			);

			$this -> konto_links = array(
				'edytujprofil'	=>		'Edytuj swoje dane',
				'trasy'		=>		'Trasy',
				'poczta'	=>		'Poczta'
			);

			$this -> admin_links = array(
				'rywalizacje'	=>		'Rywalizacje'
			);
		}


		function get() {
			return $this -> titles[my_getFilename::normal()];
		}

		function konto_links() {
			return $this->konto_links;
		}

		function admin_links() {
			return $this->admin_links;
		}
	}
?>
