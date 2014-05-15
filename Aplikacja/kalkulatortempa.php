<?php
	include('php/top.php');
?>

<header class="entry-header">
	<h1 class="entry-title">Kalkulator tempa</h1>
</header>

<article>
	<section>
	<div id="kalkulator">
			<span id="dystans" >Dystans: <input required="required" name="dystans" type="number" maxlength="6" value="0.00" id="Fd"> km</span>
			<span id="czas">Planowany czas:<br/>
							<input required="required" type="number" maxlength="2" min="0" max="99" name="godz" value="0"  id="Fh"> h
					   	    	<input required="required" type="number" maxlength="2" min="0" max="59" name="min"  value="00" id="Fm"> min
							<input required="required" type="number" maxlength="2" min="0" max="59" name="sec"  value="00" id="Fs"> sec
			</span>				 
			<span><input id="oblicz" name="oblicz" type="submit" value="Oblicz"></span>
		<div id="wynik">Wymagane tempo</div>
	</div>
	</section>
</article>
<?php
	include('php/bottom.php');
?>
