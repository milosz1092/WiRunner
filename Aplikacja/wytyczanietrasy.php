<?php
	include('php/top.php');
?>

<article>
	<section>

<?php 

if(isset($_POST['dodaj_trase'])){
	if($my_userAction->add_track(array('nazwa' => $_POST['nazwa_trasy'], 'przebieg' => $_POST['przebieg_trasy'], 'dlugosc' => $_POST['dlugosc_trasy'], 'punkty' => $_POST['punkty_trasy'])))
	$komunikat = "Pomyślnie dodano nową trasę!";
}

$wspol = $my_userAction->get_coordinates();
echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3"></script>
      <script type="text/javascript">';
 if($wspol[0] == 0) echo 'var latlng = new google.maps.LatLng(53.78,15.78);';
else echo 'var latlng = new google.maps.LatLng('.$wspol[0].','.$wspol[1].');'; ?>
var marker;
var i = 0;
var wspolrzedne = [];
var mojeUstawienia;
var dystans;
var mapa;
var dystans = 0;
var obr;
var res;
		
function inicjalizacja(x) { 

if(x == 2) latlng = (wspolrzedne[res-1]);
 mojeUstawienia = {
    zoom: 13, //!!zmienione powiekszenie!!										
    center: latlng, 								
    mapTypeId: google.maps.MapTypeId.ROADMAP 		
  };
  mapa = new google.maps.Map(document.getElementById("obszar_mapy"), mojeUstawienia);
	document.getElementsByTagName("div")['tekst'].innerHTML="Ilosc markerów na mapie: " + i +"<br>Planowany dystans: " + (dystans/1000).toFixed(3) + "km";
            
	google.maps.event.addListener(mapa, 'click', function(event) {

	wspolrzedne[i] = event.latLng;  i++;
	if(i>1) 
		dystans += distance(i-1);	 

	document.getElementsByTagName("div")['tekst'].innerHTML="Ilosc markerów na mapie: " + i +"<br>Planowany dystans: " + (dystans/1000).toFixed(3) + "km";

	if(i==1) obr="./img/web/start_marker.png";
	else obr="./img/web/red_marker.png";

	marker = new google.maps.Marker(
	{
		position: event.latLng, 
		draggable: true,
		icon: obr,
		map: mapa,
		title: "Pkt " + i + ((i > 1) ? ". \nOd poprzedniego: " + (distance(i-1)/1000).toFixed(3) + "km"+"\nOd startu: "+(dystans/1000).toFixed(3) + "km":"." + event.latLng),
		flat: false
	}); 
google.maps.event.addListener(marker, 'dragend', function() 
			{
				var patt = new RegExp("[0-9]+");
				var str = this.getTitle();
				res = parseInt(patt.exec(str));
				wspolrzedne[res-1] = (this.getPosition());


				inicjalizacja(2);
			});

new google.maps.Polyline({
				path: wspolrzedne,
				map: mapa,
				strokeColor: '#0000FF',
				strokeOpacity: 1.0,
				strokeWeight: 3
			});
}); 
	
new google.maps.Polyline({
				path: wspolrzedne,
				map: mapa,
				strokeColor: '#0000FF',
				strokeOpacity: 1.0,
				strokeWeight: 3
			});
 
var dys = 0;
for(k=0;k<i;k++)
	{
		if(k>0)
			dys += distance(k);
		if(x == 1) obr = 'none';
		else if(k==0) obr="./img/web/start_marker.png";
		else obr="./img/web/red_marker.png";

		marker = new google.maps.Marker({ 
				position: wspolrzedne[k],
				draggable: true,
				map: mapa,
				icon: obr,
				title: "Pkt " + (k+1) + ((k >= 1) ? ". \nOd poprzedniego: " + (distance(k)/1000).toFixed(3) + "km"+"\nOd startu: "+(dys/1000).toFixed(3) + "km":".")
				});
dystans = dys;
	document.getElementsByTagName("div")['tekst'].innerHTML="Ilosc markerów na mapie: " + i +"<br>Planowany dystans: " + (dystans/1000).toFixed(3) + "km";
 google.maps.event.addListener(marker, 'dragend', function() 
	{
		var patt = new RegExp("[0-9]+");
		var str = this.getTitle();
		res = parseInt(patt.exec(str));

		wspolrzedne[res-1] = (this.getPosition());

		inicjalizacja(2);
	});
	}
}

function DelMark()
     {
	if(i > 1)
	{ 
		dystans -= distance(i-1);
		i--;
		var skrajny = wspolrzedne.length - 1;
		wspolrzedne = wspolrzedne.slice(0,skrajny)
		latlng =  wspolrzedne[skrajny-1];
	} else if(i == 1){
		i = 0;
	}
		

            inicjalizacja();
     } 
 
function distance(o) {	// zwraca ilość metrów od poprzedniego markera.
	var a = wspolrzedne[o-1].toString().replace(")", "");
	var a = a.replace("(", "");
	
	var b = wspolrzedne[o].toString().replace(")", "");
	var b = b.replace("(", "");

	 a = a.split(', ',2);	
	 b = b.split(', ',2);
	
	var lat1 = a[0].toString();
	lat1 = parseFloat(lat1);
	var lat2 = b[0].toString();
	lat2 = parseFloat(lat2);
	var lon1 = a[1];
	lon1 = parseFloat(lon1);
	var lon2 = b[1];
	lon2 = parseFloat(lon2);

	var R = 6371; // km (change this constant to get miles)
	var dLat = (lat2-lat1) * Math.PI / 180;
	var dLon = (lon2-lon1) * Math.PI / 180;

	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
		Math.sin(dLon/2) * Math.sin(dLon/2);
	
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;
	return parseFloat(d*1000);
}

function save(){
	document.getElementsByTagName("div")['dodawanie_trasy'].innerHTML='\
	<form method="post" action="">\
		<span>Nazwa trasy: <input type="text" name="nazwa_trasy" required="required"></span>\
		<span>Przebieg: <textarea name="przebieg_trasy"></textarea></span>\
		<input type="hidden" name="punkty_trasy" value="'+wspolrzedne+'">\
		<input type="hidden" name="dlugosc_trasy" value="'+dystans+'">\
		<span><input type="submit" name="dodaj_trase" value="dodaj trasę"></span>\
	</form>';
}
window.onload=inicjalizacja;
</script>

<div id="obszar_mapy" style="width:600px; height:400px;"></div>

   <input type="button" onclick="inicjalizacja(1)" value="schowaj" /> 
   <input type="button" onclick="DelMark()" value="usuń ostatni marker" />
<?php
	if($_SESSION['WiRunner_log_id'] != 0)
   		echo '<input style="margin-left: 260px;" type="button" onclick="save()" value="zapisz trasę" />';
?>
   <div id="tekst" style="margin: 20px; "><?php if(isset($komunikat)) echo $komunikat . '<br/>'; ?></div>
   <div id="dodawanie_trasy" style="margin: 20px; "></div>	
	</section>
</article>
<?php
	include('php/bottom.php');
?>
