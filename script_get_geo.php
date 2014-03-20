<?php
set_time_limit(1500);

include_once 'fonctions/accesBD.php';

$link = Connection();

$sql_string = "SELECT * FROM e_commune,e_pays WHERE fk_pays=pk_pays AND lng = 0 AND lat = 0";	
$sql = sprintf($sql_string);
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));

$nb = 0;

while($commune = mysqli_fetch_array($req)){	
	$tab = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode(htmlentities($commune['ville'])." ".$commune['pays'])),true);
	if($tab['status']=="OK") {	
		$sql_string = "UPDATE e_commune SET lng='%s',lat='%s' WHERE pk_commune=%s";
		$sql = sprintf($sql_string,$tab['results'][0]['geometry']['location']['lng'],$tab['results'][0]['geometry']['location']['lat'],$commune['pk_commune']);
		mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
		$nb++;
	} else {
		echo $commune['pk_commune']." ".$commune['ville']." -> ";
		echo $tab['status']."<br />";
	}
}

echo ($nb);

Deconnection($link);

echo "<br />END";

?>