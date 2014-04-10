<?php
	final class my_mainMenu {
		private $links_normal;
		private $links_offline;
		private $links_online;

		function __construct() {			
			$this -> links_normal = array(
				'.'			=> 'Strona Główna'
				
			);
			$this -> links_offline = array(
				'register.php'		=> 'Rejestracja',
				'login.php'			=> 'Logowanie'	
			);
			$this -> links_online = array(
				'php/logout.php'		=> 'Wyloguj',
				'konto.php'			=> 'Moje konto',
				'kalkulatortempa.php'		=> 'Kalkulator tempa'
			);
		}

		public function drukuj($log_session) {
			foreach($this -> links_normal as $link => $nazwa) {
				if ($link == '.') $link = 'index.php';
				echo '<a '; if(my_getFilename::normal() == $link) echo 'class="menu_act" '; 
				if ($link == 'index.php') $link = '.';
				echo 'href="'.$link.'">'.$nazwa.'</a>';
			}

			if($log_session == 0) {
				foreach($this -> links_offline as $link => $nazwa) {
					echo '<a '; if(my_getFilename::normal() == $link) echo 'class="menu_act login_link" '; else echo 'class="login_link" '; echo 'href="'.$link.'">'.$nazwa.'</a>';
				}
			}
			
			if($log_session > 0) {
				foreach($this -> links_online as $link => $nazwa) {
					echo '<a '; if(my_getFilename::normal() == $link) echo 'class="menu_act login_link" '; else echo 'class="login_link" '; echo 'href="'.$link.'">'.$nazwa.'</a>';
				}
			}
			
		}
	}
?>
