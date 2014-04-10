<?php
	include('php/top.php');
?>
	<div id="kalkulator">
		<h2>Kalkulator Tempa</h2>
			<span id="dystans" >Dystans: <input name="dystans" type="number" maxlength="6" value="0.00" id="Fd"> km</span>
			<span id="czas">Planowany czas:<br/>
							<input type="number" maxlength="2" min="0" max="99" name="godz" value="0"  id="Fh"> h
					   	    <input type="number" maxlength="2" min="0" max="59" name="min"  value="00" id="Fm"> min
							<input type="number" maxlength="2" min="0" max="59" name="sec"  value="00" id="Fs"> sec
			</span>				 
			<span><input id="oblicz" name="oblicz" type="submit" value="Oblicz"></span>
		<div id="wynik">Wymagane tempo</div>
	</div>
<?php
	include('php/bottom.php');
?>
