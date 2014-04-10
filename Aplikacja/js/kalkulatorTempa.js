$( document ).ready(function() {
	$( "#oblicz" ).click(function( event ) {
		
		$dystans = parseFloat($( "#Fd" ).val());
		$godzin = parseFloat($( "#Fh" ).val());
		$minut = parseFloat($( "#Fm" ).val());
		$sekund = parseFloat($( "#Fs" ).val());
		
		if($.isNumeric( $dystans ) && $.isNumeric( $godzin ) && $.isNumeric( $minut ) && $.isNumeric( $sekund ) 
		&& $dystans > 0 && $godzin >= 0 && $minut >= 0 && $sekund >= 0 &&
		$minut < 60 && $sekund < 60 &&
		($godzin > 0 || $minut > 0 || $sekund > 0))
		{
			$tempo = (60*$dystans)/(60*$godzin+$minut+$sekund/60);
			$("#wynik").html("Wymagane tempo: <b>"+ $tempo.toFixed(2) + "</b> km/h");
		} else {
			$("#wynik").html("Wprowadź prawidłowe wartości!");
		}
	});
});
