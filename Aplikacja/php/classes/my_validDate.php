<?php
	abstract class my_validDate {
		static function wymagane($dane) {
			$poprawne = true;

			foreach($dane as $typ => $tresc) {
				if(!$tresc) {
					$poprawne = false;
					break;
				}
			}

			return $poprawne;
		}

		static function specjalne($dane) {
			$poprawne = true;
			
			$wzorzec = '/[~\s,.:;"\]\[}{=\\\\\/\n\t`\'<>?!@#$%^&*()_+|-]/';
			
			foreach($dane as $typ => $tresc) {
				if (@preg_match_all($wzorzec, $tresc)) {
					$poprawne = false;
					break;
				}
			}
		
			return $poprawne;
		}

		static function cenzura($dane) {
			$poprawne = true;

			$wulgaryzmy = array (
				'kurwa',
				'kurwi',
				'kurwy',
				'kurwo',
				'jebać',
				'jebac',
				'jebany',
				'jebani',
				'jebane',
				'jebię',
				'jebie',
				'jebaka',
				'jebaką',
				'jebakę',
				'jebake',
				'huj',
				'chuj',
				'pierdol',
				'pierdala',
				'pizda',
				'ruchać',
				'ruchac',
				'ruchał',
				'ruchal',
				'rucham',
				'cipa',
				'cipie',
				'cipy',
				'cipą',
				'cipę'.
				'cipe',
				'cipo',
				'cipci',
				'cipka',
				'cipce',
				'cipki',
				'cipkę',
				'cipke',
				'cipko',
				'cipek',
				'kutas',
				'pierdziel',
				'osra',
				'srac',
				'srać',
				'sral',
				'srał',
				'sraniec',
				'srane',
				'szczac',
				'szczać',
				'szczal',
				'szczał',
				'szczane',
				'szczyn',
				'pierdoł',
				'pierdół',
				'suka',
				'sukę',
				'suką',
				'suke',
				'lachociag',
				'lachociąg',
				'dziwka',
				'burdel',
				'cwel',
				'dup',
				'udupi',
				'gówn',
				'gown',
				'gowien',
				'gówien',
				'skurczysyn',
				'sukinsyn',
				'skurwiel',
				'skurwysyn',
				'porno',
				'pieprze',
				'pieprzy',
				'pieprzę',
				'spieprzaj',
				'dupa'
			);

			foreach($wulgaryzmy as $id => $tekst) {
				$wzorzec = '/'.$tekst.'/';
				
				foreach($dane as $klucz => $zawartosc) {
					if(preg_match($wzorzec, mb_strtolower($zawartosc))) {
						$poprawne = false;
						break;
					}

					if($poprawne == false)
						break;
				}
			}
			

			return $poprawne;
		}

		static function polskie($dane) {
			$poprawne = true;
			
			$wzorzec = '/[ąćęłńóśżź]{2}/';
			$wzorzec2 = '/[ĄĆĘŁŃÓŚŻŹ]{2}/';

			foreach($dane as $typ => $tresc) {
				if (preg_match($wzorzec, $tresc)) {
					$poprawne = false;
					break;
				}

				if (preg_match($wzorzec2, $tresc)) {
					$poprawne = false;
					break;
				}
			}
			
			return $poprawne;
		}

		static function porownaj($dane) {
			$poprawne = true;

			if($dane[0] != $dane[1])
				$poprawne = false;
				
			return $poprawne;
		}

		static function email($dane) {
			$poprawne = true;

			$wzorzec = '/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/';
	
			if(!preg_match($wzorzec, $dane[0]) && $dane[0] != '') 
				$poprawne = false;
				
			return $poprawne;
		}

		static function dlugoscmin($dane, $dlugosc) {
			$poprawne = true;

			foreach($dane as $typ => $tresc) {
				if(strlen(utf8_decode($tresc)) < $dlugosc && $tresc != '')
					$poprawne = false;
			}
				
			return $poprawne;
		}

		static function dlugoscmax($dane, $dlugosc) {
			$poprawne = true;

			foreach($dane as $typ => $tresc) {
				if(strlen(utf8_decode($tresc)) > $dlugosc && $tresc != '')
					$poprawne = false;
			}
				
			return $poprawne;
		}

		static function nie_duze($dane) {
			$poprawne = true;
			
			$wzorzec = '/([A-Z])/';

			foreach($dane as $id => $tresc) {
				if(preg_match($wzorzec, $tresc)) {
					$poprawne = false;
					break;
				}
			}

			return $poprawne;
		}

		static function stringi($dane, $dlugosc) {
			$poprawne = false;
			$licznik = 0;

			foreach($dane as $typ => $tresc) {
				$znaki = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M');

				for($i = 0; $i < strlen(utf8_decode($tresc)); $i++)
					if(in_array($tresc[$i], $znaki)) {
						$licznik++;

						if($licznik == $dlugosc) {
							$poprawne = true;
							break;
						}
					}		
			}

			if($tresc == '')
				$poprawne = true;
				
			return $poprawne;
		}

		static function zle_nazwy($login) {
			$poprawne = true;

			$zakazane = array('admin', 'administrator', 'moderator');
			
			foreach($zakazane as $id => $nazwa) {
				$wzorzec = '/'.$nazwa.'/';
		
				if(preg_match($wzorzec, $login))
					$poprawne = false;
			}
				
			return $poprawne;
		}

		static function check_count($dane, $ile) {
			$poprawne = true;
			$elementy = 0;

			if($dane > 0) {
				$elementy = count($dane);

				if($elementy < $ile)
					$poprawne = false;
			}

			return $poprawne;
		}
		
	}
?>
