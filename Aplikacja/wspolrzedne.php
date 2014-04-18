<?php
	include('php/top.php');
?>

<article>
	<section>
<?php 
if(isset($_POST['ustaw'])){
	if($my_userAction->set_coordinates(array('szerokosc' => $_POST['szerokosc'], 'dlugosc' => $_POST['dlugosc'])))
	$komunikat = "Pomyślnie zaktualizowano!";
}

$wspol = $my_userAction->get_coordinates();
echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3"></script>
      <script type="text/javascript">';
 if($wspol[0] == 0) echo 'var latlng = new google.maps.LatLng(53.78,15.78);';
else echo 'var latlng = new google.maps.LatLng('.$wspol[0].','.$wspol[1].');'; ?>
var marker=0;
var i = 0;
var wspolrzedne;
var mojeUstawienia;
var dystans;
var mapa;
var x;

function inicjalizacja(x) { 

 mojeUstawienia = {
    zoom: 10, 									
    center: latlng, 								
    mapTypeId: google.maps.MapTypeId.ROADMAP 		
  };
  mapa = new google.maps.Map(document.getElementById("obszar_mapy"), mojeUstawienia);
           
new google.maps.Marker(
	{
		position: latlng, 
		map: mapa,
		draggable: false,
		title: 'Aktualna pozycja',
		flat: false,
		icon: './img/web/marker.png'
	});

 
	google.maps.event.addListener(mapa, 'click', function(event) {

	wspolrzedne = event.latLng;
	var a = wspolrzedne.toString().replace(")", "");
	a = a.replace("(", "");
	a = a.split(', ',2);

	document.getElementsByTagName("div")['wspolrzedne'].innerHTML= 'Współrzedne punktu: <br>\
		<form name="ustawianieWspolrzednych" method="post" action="">\
			<input readonly name="szerokosc" type="text" value="'+parseFloat(a[0]).toFixed(5)+'">\
			<input readonly name="dlugosc" type="text" value="'+parseFloat(a[1]).toFixed(5)+'">\
			<input name="ustaw" type="submit" value="ustaw">\
		</form>';

var marker_stary=marker;

	marker = new google.maps.Marker(
	{
		position: event.latLng, 
		map: mapa,
		draggable: false,
		title: parseFloat(a[0]).toFixed(5) + ' ' + parseFloat(a[1]).toFixed(5),
		flat: false
	});

//	mapa.setCenter( new google.maps.LatLng(parseFloat(a[0]), parseFloat(a[1])) );

if(marker_stary)
	marker_stary.setMap(null);
});
}
window.onload=inicjalizacja;
</script>
 
<div id="obszar_mapy"></div>
<div id="wspolrzedne"><?php if(isset($komunikat)) echo $komunikat . '<br/>'; ?>Kliknij na mapie, by ustawić nowe współrzędne!</div>
	</section>
</article>
<?php
	include('php/bottom.php');
?>
