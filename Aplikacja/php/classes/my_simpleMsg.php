<?php
	abstract class my_simpleMsg {
		static function show($title, $tresc, $input_id) {
			if($title != '') {
				echo '<header class="entry-header hr_bor">';
					echo '<h1 class="entry-title">'.$title.'</h1>';
				echo '</header>';
			}
			echo '<ul';
				if ($input_id != '0')
					echo " class=\"$input_id\" ";
			echo '>';
				foreach($tresc as $id => $zawartosc) {
					echo '<li>'.$zawartosc.'</li>';
				}
			echo '</ul>';
		}
	}
?>
