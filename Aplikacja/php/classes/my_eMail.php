<?php
	abstract class my_eMail {
		static function send($tresc, $mail, $to, $topic, $action) {
			if(!my_validDate::wymagane(array($tresc)))
				$bledy[] = 'Wysyłanie wiadomości bez treści nie ma sensu';

			if(!my_validDate::wymagane(array($mail)))
				$bledy[] = 'Podaj swój adres e-mail abym mógł Ci odpisać';

			// klasa sprawdzajaca czy podane konto istnieje

			if(!my_validDate::dlugoscmin(array($tresc), 3))
				$bledy[] = 'Twoja wiadomość zawiera za mało znaków';

			if(!isset($bledy)) {
				$to      = $to;
				$subject = '=?UTF-8?B?'.base64_encode($topic).'?=';
				$message = $tresc;
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'From: '.$mail.' <'.$mail.'>' . "\r\n" ;

				if (mail($to, $subject, $message, $headers))
					switch($action) {
						case 'passreset':
							my_simpleMsg::show('Wysłaliśmy link resetujący!', array('Kliknij w przesłane hiperłącze aby zresetować hasło', 'Pamiętaj, że nasza wiadomość może trafić do spamu'), 0);
						break;
					}
				else
					$bledy[] = 'Błąd serwera wysyłającego wiadomość';
			}

			if(isset($bledy) && count($bledy) > 0)
				my_simpleMsg::show('Nie udało się wysłać wiadomości!', $bledy, 0);
		}
	}
?>
