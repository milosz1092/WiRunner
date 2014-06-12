<?php
	include('php/top.php');

	if(empty($_GET['id']) || !intval($_GET['id']) || ($dane=$my_activities->getActivityById($_GET['id'])) == 0)
		header("Location: szukaj.php");

	if($_SESSION['WiRunner_log_id'] == 0 && $dane['widoczna_dla_gosci'] == 0)
		header("Location: login.php");

	$dane = $my_activities->getActivityById($_GET['id']);

	if($_SESSION['WiRunner_log_id'] != 0 && $_SESSION['WiRunner_log_id'] != $dane['nr_uzytkownika'] && in_array($my_usersRelations->zwroc_typ(
			array("1st" => $_SESSION['WiRunner_log_id'],
			      "2nd" => $dane['nr_uzytkownika'])), array("Wróg", "Blokowany")))
		header("Location: index.php");

	if(isset($_POST['dodajKomentarz'])){

	$resDodawania = $my_comments->dodajKoment(
								array(
									'id' => $_POST['id'],
								      	'komentarz' => $_POST['komentarz'],
								     	'rodzaj' => $_POST['rodzaj']
									));
	}
	else if(isset($_GET['action']) && $_GET['action'] == "usun_komentarz" && isset($_GET['koment_id']) && intval($_GET['koment_id']) )
	{
		$res = $my_comments->removeComment("doAktywnosci", $_GET['koment_id']);

		if($res == 1)
			echo '<div class="ok_msg">Komentarz pomyślnie usunięty!</div>';
		else if($res == 0)
		 echo '<div class="wrong_msg">Usuwanie zakończone niepowodzeniem!</div>';
		else if($res == -1)
		 echo '<div class="wrong_msg">Nie masz uprawnień!</div>';
	}else if(isset($_GET['action']) && $_GET['action'] == "polub")
	{
		$res = $my_comments->dodajPolubienie($_GET['id']);

		if($res == 1)
			echo '<div class="ok_msg">Polubienie pomyślnie dodane!</div>';
		else if($res == 0)
		 echo '<div class="wrong_msg">Polubianie zakończone niepowodzeniem!</div>';
		else if($res == -1)
		 echo '<div class="wrong_msg">Nie masz uprawnień!</div>';
	}


$user_info = $my_activities->getUserInfo($dane['nr_uzytkownika']);
$czas = (3600 * $dane['dystans']) / $dane['tempo'];

	echo '<div class="aktywnoscSzczegoly">
		<img style="display:inline-block;float:left;margin-right:10px;" width="20" height="20" src="img/web/unknow.jpg" alt="avatar" />

<a href="profil.php?uid='.$dane['nr_uzytkownika'].'"><b>'.(isset($user_info['imie'])?$user_info['imie'] . ' ' . $user_info['nazwisko'] : $user_info['email']) . '</b></a> uprawiał <u>' . $my_activities->getSport($dane['nr_sportu']) . '</u>. ';
			
	echo 'Przebył '. $dane['dystans'] . 'km w ' . $my_activities->formatujCzas($czas) .'.<br/><br/>';

	if($my_comments->czyJestJuzPolubione($_GET['id']) == 0)
		echo '<a href="'.my_getFilename::normal().'?id='.$_GET['id'].'&action=polub">Lubię to!</a><br/><br/>';
	


	echo 'Data treningu: '.$dane['data_treningu'].'<br/>
		Nazwa treningu: <b>'.$dane['nazwa_treningu'].'</b><br/>
		Opis: '.$dane['opis'].'<br/><br/>
		Data dodania: '.$dane['data_dodania'].'</div>';
	if($dane['nr_trasy'])
	{
	
	$dane_trasy = $my_userAction->get_track($dane['nr_trasy']);
	echo '<div id="obszar_mapy" class="float: right">';
	$wsp = explode("),", $dane_trasy['punkty_trasy']);
	echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3"></script>
	      <script type="text/javascript">
		var latlng = new google.maps.LatLng'.substr($dane_trasy['punkty_trasy'], 0, strpos($dane_trasy['punkty_trasy'], ")")+1).';
		var wspolrzedne =new Array();
		var dystans = '.($dane_trasy['dlugosc_trasy']*1000).';
		var i = '.sizeof($wsp).';';
		foreach($wsp as $key => $punkt)
			echo 'wspolrzedne['.$key.'] =  new google.maps.LatLng'.$punkt.($key+1 != sizeof($wsp)?')':'').';';
?>
	
var marker;
var mojeUstawienia;
var mapa;
var trasa;

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

}alert("ok");
</script>
<?php
}
	echo '</div>';

	if(isset($resDodawania) && is_array($resDodawania))
	my_simpleMsg::show('Błedy danych!', $resDodawania, 0);
	echo $my_comments->formularzDodawania("doAktywnosci",$_GET['id']);
	
	$my_comments->printComments("doAktywnosci", $_GET['id']);


	include('php/bottom.php');
?>
