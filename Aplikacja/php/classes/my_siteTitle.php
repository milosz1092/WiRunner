<?php
	final class my_siteTitle {
		private $titles;
		private $konto_links;
		private $admin_links;

		function __construct () {
			$this -> titles = array(
				'index.php'		=> 'Strona Główna',
				'konto.php'		=> 'Moje konto',
				'profil.php'		=> 'Profil',
				'admin.php'		=> 'Administracja',
				'kontakt.php'		=> 'Kontakt',
				'register.php'		=> 'Rejestracja',
				'login.php'		=> 'Logowanie',
				'regulamin.php'		=> 'Regulamin',
				'passreset.php'		=> 'Reset hasła',
				'kalkulatortempa.php'	=> 'Kalkulator tempa',
				'szukaj.php'		=> 'Wyszukiwarka',
				'wspolrzedne.php'	=> 'Ustawianie startu tworzenia trasy',
				'trasy.php'		=> 'Przeglądaj trasy',
				'wytyczanietrasy.php'	=> 'Wyznacz trasę dla swoich aktywności',
				'dodajaktywnosc.php'	=> 'Dodaj aktywność',
				'aktywnosc.php'		=> 'Przeglądanie aktywności'
			);

			$this -> konto_links = array(
				'edytujprofil'	=>	array('Edytuj swoje dane', NULL),
				'trasy'			=>	array ('Trasy', NULL),
				'urywalizacje'		=>	array ('Rywalizacje', NULL),
				'poczta'		=>	array ('Poczta', NULL),
				'przyjaciele'		=>	array ('Przyjaciele', NULL),
				'zaproszenia'		=>	array ('Zaproszenia', NULL),
				'polubione'		=>	array ('Polubione', NULL),
				'chpass'		=>	array ('Zmiana hasła', NULL),
				'delacount'		=>	array ('Usuń konto', NULL),
			);

			$this -> admin_links = array(
				'rywalizacje'	=>	array ('Rywalizacje', array(
					'add'			=>		'dodaj'
				)),
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
