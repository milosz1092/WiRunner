<?php
	include('php/top.php');
?>

<article>
	<section>

<?php 
if(isset($_GET['id']) && is_numeric($_GET['id'])) $id_trasy = $_GET['id'];
else header("Location: ./index.php");
$dane = $my_userAction->get_track($id_trasy);
if(empty($dane['punkty_trasy'])) echo "Brak mapy dla tej trasy.";
else
{
$wsp = explode("),", $dane['punkty_trasy']);
echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3"></script>
      <script type="text/javascript">
        var latlng = new google.maps.LatLng'.substr($dane['punkty_trasy'], 0, strpos($dane['punkty_trasy'], ")")+1).';
	var wspolrzedne = [];
	var i = '.sizeof($wsp).';';
	foreach($wsp as $key => $punkt)
		echo 'wspolrzedne['.$key.'] =  new google.maps.LatLng'.$punkt.($key+1 != sizeof($wsp)?')':'').';';
?>
var marker;
var mojeUstawienia;
var dystans;
var mapa;
var x;

function inicjalizacja() { 

 mojeUstawienia = {
    zoom: 14, //!!zmienione powiekszenie!!										
    center: latlng, 								
    mapTypeId: google.maps.MapTypeId.ROADMAP 		
  };
  mapa = new google.maps.Map(document.getElementById("obszar_mapy"), mojeUstawienia);
	
new google.maps.Polyline({
path: wspolrzedne,
strokeColor: '#0000FF',
strokeOpacity: 1.0,
strokeWeight: 3,
map: mapa
});

var dys = 0;
var obr;
for(k=0;k<i;k++)
{
	if(k>0)
	dys += distance(k);


	if(k==0) obr="./img/web/start_marker.png";
	else if(k == i-1) obr="./img/web/meta_marker.png";
	else obr="./img/web/red_marker.png";

	marker = new google.maps.Marker(
	   { 
		icon: obr, 
		position: wspolrzedne[k],
		map: mapa,
		title: "Pkt " + (k+1) + ((k >= 1) ? ". \nOd poprzedniego: " + (distance(k)/1000).toFixed(3) + "km"+"\nOd startu: "+(dys/1000).toFixed(3) + "km":"."),
		flat: false
	   });
}


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
window.onload=inicjalizacja;
</script>

<div id="obszar_mapy" style="width:600px; height:400px;"></div>
<?php } ?>
		<div id="tekst" style="margin: 20px; ">
			Nazwa trasy: <b><?php echo $dane['nazwa_trasy']; ?></b><br/>
			Przebieg: <b><?php echo $dane['przebieg_trasy']; ?></b><br/>
			Długość: <b><?php echo $dane['dlugosc_trasy']; ?>km</b><br/>
			Data dodania: <b><?php echo $dane['data_dodania']; ?></b><br/>
		</div>
	</section>
</article>
<?php
	include('php/bottom.php');
?>
