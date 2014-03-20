<?php
include_once 'includes/header_php.php';

include_once 'fonctions/accesBD.php';


$link = Connection();

$sql_string = "SELECT * FROM e_commune,e_resultat,e_election WHERE fk_election=pk_election AND fk_election=1 AND fk_pays=1 AND fk_lieu=pk_commune AND (lng <> 0 AND lat <> 0)";	
$sql = sprintf($sql_string);
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
while($res = mysqli_fetch_array($req)){
	$liste_res[] = $res;
}

Deconnection($link);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  
	<head>
	
		<?php include_once "includes/head_html.php"; ?>	
		
		<meta name="Description" content="Elections Municipales" />	
		<meta name="Keywords" content="Elections Municipales" />	
		<title>Elections Municipales</title>
	
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
		
		<script type="text/javascript">
		function initialize() {	
			var map_election = new google.maps.Map(document.getElementById("map_canvas"), {center: new google.maps.LatLng(47,2),zoom:8});		 	
			//var bounds = new google.maps.LatLngBounds();	
			<?php 
				for($i=0;$i<sizeof($liste_res);$i++){ 
					if(!$liste_res[$i]['nb_inscrits'] || !($liste_res[$i]['nb_inscrits']-$liste_res[$i]['nb_votants'])) continue;
					$tau = round(log($liste_res[$i]['nb_votants']/($liste_res[$i]['nb_inscrits']-$liste_res[$i]['nb_votants'])),2);
					$tau_normalise = round(($liste_res[$i]['param_1']*log($liste_res[$i]['nb_inscrits']))+$liste_res[$i]['param_2'],2);								
					$value = $tau-$tau_normalise;	
					if($value>0) $value = "+".$value;		
					$value = str_replace(".",",",$value);	
					//$liste_res[$i]['ville'] = str_replace("'","\'",$liste_res[$i]['ville']);	
			?>		
				addMarker(<?php echo $liste_res[$i]['lat']; ?>,<?php echo $liste_res[$i]['lng']; ?>,"<?php echo $value."(".$liste_res[$i]['ville'].")"; ?>");
			<?php } ?>			
			function addMarker(lat,lng,tit){
				var coords = new google.maps.LatLng(lat,lng);				
				var marker = new google.maps.Marker({
					'position':coords,
					'title':'',
					'zIndex':0,
					'scale':2,
					'z-index':0,
					'map':map_election
				});						
				var infowindow = new google.maps.InfoWindow({					
					'position':new google.maps.LatLng(lat,lng),					
					'content':tit
				});					
				google.maps.event.addListener(marker, 'mouseover', function() {	
					infowindow.open(map_election, marker);
					marker.setOptions( {'zIndex' : 10});
				});
				google.maps.event.addListener(marker, 'mouseout', function() {		
					infowindow.close(map_election, marker);
					marker.setOptions( {'zIndex' : 0});
				});
			}			
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		
	</head> 

	<body>		
		
		<div id="container">
		
			<div id="header">
				<?php include 'includes/header.php'; ?>
			</div>					
			RESULTAT ELECTIONS MUNICIPALES FRANCE 2008
			<div id="map_canvas" style="height: 500px;width:100%;"></div>				
			
			<?php echo "Nb résultats : ".sizeof($liste_res); ?>
			
			<div id="footer">
				<?php include 'includes/footer.php'; ?>
			</div>
			
		</div>		
		
	</body>
	
</html>