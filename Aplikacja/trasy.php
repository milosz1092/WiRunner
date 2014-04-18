<?php
	include('php/top.php');
?>

<article>
	<section>

<?php 
if(isset($_GET['id']) && is_numeric($_GET['id'])) $id_trasy = $_GET['id'];
else header("Location: index.php");


if(isset($_POST['edytuj_trase'])){
	if($my_userAction->edit_track(array('id_trasy'=> $id_trasy, 'nazwa' => $_POST['nazwa_trasy'], 'przebieg' => $_POST['przebieg_trasy'], 'dlugosc' => $_POST['dlugosc_trasy'], 'punkty' => $_POST['punkty_trasy'])))
	$komunikat = "Pomyślnie dodano nową trasę!";
}

$dane = $my_userAction->get_track($id_trasy);
if($dane == 0) echo "Brak trasy o podanym ID albo nie masz praw do jej przeglądania!";
else {
if(empty($dane['punkty_trasy'])) echo "Brak mapy dla tej trasy.";
else
{
$wsp = explode("),", $dane['punkty_trasy']);
echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3"></script>
      <script type="text/javascript">
        var latlng = new google.maps.LatLng'.substr($dane['punkty_trasy'], 0, strpos($dane['punkty_trasy'], ")")+1).';
	var wspolrzedne = [];
	var dystans = '.($dane['dlugosc_trasy']*1000).';
	var i = '.sizeof($wsp).';';
	foreach($wsp as $key => $punkt)
		echo 'wspolrzedne['.$key.'] =  new google.maps.LatLng'.$punkt.($key+1 != sizeof($wsp)?')':'').';';
?>
var marker;
var mojeUstawienia;
var mapa;
var trasa;
var czyPrzesuwany;
var res=1; // ostatnio przesuwany marker

function inicjalizacja(x=0) {
czyPrzesuwany=(x==1? true : false );
if(x==1) { latlng = (wspolrzedne[res-1]);

		document.getElementById("pkty_trasy").value = (wspolrzedne);
		document.getElementById("dyst_trasy").value = (dystans);
} else if(x == 2) {
document.getElementsByTagName("div")['kontrolki'].innerHTML= '\
		<input type="button" onclick="inicjalizacja(3)" value="Pokaż markery"><br/>\
		<input type="button" onclick="editForm()" value="Edytuj trasę">';
} else if(x == 3) {
document.getElementsByTagName("div")['kontrolki'].innerHTML= '\
		<input type="button" onclick="inicjalizacja(2)" value="schowaj markery" /><br/>\
		<input type="button" onclick="editForm()" value="Edytuj trasę">';
}
 mojeUstawienia = {
    zoom: 14, //!!zmienione powiekszenie!!										
    center: latlng, 								
    mapTypeId: google.maps.MapTypeId.ROADMAP 		
  };
  mapa = new google.maps.Map(document.getElementById("obszar_mapy"), mojeUstawienia);
	
// ------------------

	google.maps.event.addListener(mapa, 'click', function(event) {
if(x == 1){
	wspolrzedne[i] = event.latLng;  i++;
	if(i>1) 
		dystans += distance(i-1);	 

	document.getElementsByTagName("div")['t1'].innerHTML="Ilosc markerów na mapie: " + i +"<br>Planowany dystans: " + (dystans/1000).toFixed(3) + "km";

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

				inicjalizacja(1);
			});

trasa = new google.maps.Polyline({
				path: wspolrzedne,
				map: mapa,
				strokeColor: '#0000FF',
				strokeOpacity: 1.0,
				strokeWeight: 3
			});
	}
	
		document.getElementById("pkty_trasy").value = (wspolrzedne);
		document.getElementById("dyst_trasy").value = (dystans);
}); 


// ------------------

new google.maps.Polyline({
path: wspolrzedne,
strokeColor: '#0000FF',
strokeOpacity: 1.0,
strokeWeight: 3,
map: mapa
});

var dys = 0;
var obr;
if(x != 2)
for(k=0;k<i;k++)
{
	if(k>0)
	dys += distance(k);

	if(k==0) obr="./img/web/start_marker.png";
	else if(k == i-1 && x!=1) obr="./img/web/meta_marker.png";
	else obr="./img/web/red_marker.png";

	marker = new google.maps.Marker(
	   { 
		draggable: czyPrzesuwany,
		icon: obr, 
		position: wspolrzedne[k],
		map: mapa,
		title: "Pkt " + (k+1) + ((k >= 1) ? ". \nOd poprzedniego: " + (distance(k)/1000).toFixed(3) + "km"+"\nOd startu: "+(dys/1000).toFixed(3) + "km":"."),
		flat: false
	   });
// -----
	dystans = dys;
if(x == 1)
	document.getElementsByTagName("div")['t1'].innerHTML="Ilosc markerów na mapie: " + i +"<br>Planowany dystans: " + (dystans/1000).toFixed(3) + "km";
 google.maps.event.addListener(marker, 'dragend', function() 
	{
		var patt = new RegExp("[0-9]+");
		var str = this.getTitle();
		res = parseInt(patt.exec(str));

		wspolrzedne[res-1] = (this.getPosition());
		inicjalizacja(1);
	});
// ------

	}

		document.getElementById("pkty_trasy").value = (wspolrzedne);
		document.getElementById("dyst_trasy").value = (dystans);

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
		if(i>0)
			res = i;
	
            inicjalizacja(1);
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

function editForm(){
<?php 
	echo 'var nazwa_trasy = "'.$dane['nazwa_trasy'].'";
	      var przebieg_trasy = "'.$dane['przebieg_trasy'].'";'; 
?>
document.getElementsByTagName("div")['tekst'].innerHTML= '\
		<form id="edycjaTrasy" method="post" action="">\
			<span>Nazwa trasy: <input type="text" name="nazwa_trasy" value="'+nazwa_trasy+'" required="required" maxlength="36"></span>\
			<span>Przebieg: <textarea name="przebieg_trasy">'+przebieg_trasy+'</textarea></span>\
			<input id="pkty_trasy" type="hidden" name="punkty_trasy" value="'+wspolrzedne+'">\
			<input id="dyst_trasy" type="hidden" name="dlugosc_trasy" value="'+dystans+'">\
			<span><input type="submit" name="edytuj_trase" value="Edytuj trasę"></span>\
		</form>';
document.getElementsByTagName("div")['kontrolki'].innerHTML= '\
		<input type="button" onclick="DelMark()" value="usuń ostatni marker" />';
inicjalizacja(1);
}
window.onload=inicjalizacja;
</script>

<div id="obszar_mapy" style="width:600px; height:400px;"></div>
<?php 
} 
if($dane['nr_uzytkownika'] == $_SESSION['WiRunner_log_id'])
echo '<div id="kontrolki" style="margin-left: 440px;"><input type="button" onclick="inicjalizacja(2)" value="schowaj markery" /> <br/>
						      <input type="button" onclick="editForm()" value="Edytuj trasę"></div>';
?>
		<div id="t1" style="margin: 20px;clear: both;"></div>
		<div id="tekst" style="margin: 20px; ">
			Nazwa trasy: <b><?php echo $dane['nazwa_trasy']; ?></b><br/>
			Przebieg: <b><?php echo $dane['przebieg_trasy']; ?></b><br/>
			Długość: <b><?php echo $dane['dlugosc_trasy']; ?>km</b><br/>
			Data dodania: <b><?php echo $dane['data_dodania']; ?></b><br/>
		</div>
<? } ?>
	</section>
</article>
<?php
	include('php/bottom.php');
?>
