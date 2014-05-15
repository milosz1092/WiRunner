<?php
	final class my_activities extends my_connDb {
		protected $pdo;
	
		function formularzDodawania($sporty)
		{
			if(empty($sporty)) return -1;

			$pola = array(
					array('nazwa_treningu','Imię','text','45','req'),
				      	array('opis','Krótki opis','textarea','45','req'),
				     	array('tempo','Tempo km/h','number','4', 'req'),
					array('dystans','Dystans','number','6','req')
			);

			echo '<form action="" method="post">
				<ul class="form_field">';


				foreach($pola as $ele)
				{
					echo '<li>
						<label for="'.$ele[0].'" style="text-align: right; padding-right: 10px;">'.$ele[1].':</label>';
					if($ele[2] != "textarea")
						echo	'<input type="'.$ele[2].'" id="'.$ele[0].'" name="'.$ele[0].'" value="'.$userInfo[$ele[0]].'" maxlength="'.$ele[3].'" '.(isset($ele[4])? 'required="required"':"").'/>';
					else	echo	'<textarea id="'.$ele[0].'" name="'.$ele[0].'" maxlength="'.$ele[3].'"/>'.$userInfo[$ele[0]].'</textarea>';
					    	echo '</li>';
				}
			echo '<input style="margin: 20px 0px 0px 140px;" type="submit" value=" dane" name="edytujDane"></ul></form>';
			echo '<script>';
				echo '$("#imie").focus();';
			echo '</script>';
		}

	}
?>
