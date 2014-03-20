<?php
include_once 'includes/header_php.php';

include_once 'fonctions/accesBD.php';

$link = Connection();

$sql = "SELECT * FROM e_serie WHERE fk_type_serie=1";	
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
while($res = mysqli_fetch_array($req)){
	$liste_res[] = $res;
}

$sql = "SELECT * FROM e_serie WHERE fk_type_serie=2";	
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res_2 = array();
while($res = mysqli_fetch_array($req)){
	$liste_res_2[] = $res;
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
		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['nb_inscrits', 'tau'],
				<?php 
					for($i=0;$i<sizeof($liste_res)-1;$i++){
						echo '['.$liste_res[$i]['x'].','.$liste_res[$i]['y'].'],';					
					}					
					$i = sizeof($liste_res)-1;					
					echo '['.$liste_res[$i]['x'].','.$liste_res[$i]['y'].']';
				?>	
			]);

			var options = {
			  title: 'tau = f(nb_inscrits)',
			  hAxis: {title: 'nb_inscrits', minValue: 0, maxValue: 1,logScale:true},
			  vAxis: {title: 'tau', minValue: 0, maxValue: 1},			 
			  legend: 'none'
			};

			var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
			chart.draw(data, options);
			
			var data = google.visualization.arrayToDataTable([
				['nb_inscrits', 'tau'],
				<?php 
					for($i=0;$i<sizeof($liste_res_2)-1;$i++){
						echo '['.$liste_res_2[$i]['x'].','.$liste_res_2[$i]['y'].'],';					
					}					
					$i = sizeof($liste_res_2)-1;					
					echo '['.$liste_res_2[$i]['x'].','.$liste_res_2[$i]['y'].']';
				?>	
			]);

			var options = {
			  title: 'tau = f(nb_inscrits)',
			  hAxis: {title: 'nb_inscrits', minValue: 0, maxValue: 1,logScale:true},
			  vAxis: {title: 'tau', minValue: 0, maxValue: 1},			 
			  legend: 'none'
			};

			var chart2 = new google.visualization.ScatterChart(document.getElementById('chart_div_2'));
			chart2.draw(data, options);
			
		  }		 
		</script>

		
	</head> 

	<body>		
		
		<div id="container">
		
			<div id="header">
				<?php include 'includes/header.php'; ?>
			</div>					
			RESULTAT ELECTIONS MUNICIPALES FRANCE 2001
			<div id="chart_div" style="width: 900px; height: 500px;"></div>
			
			RESULTAT ELECTIONS MUNICIPALES FRANCE 2008
			<div id="chart_div_2" style="width: 900px; height: 500px;"></div>
			
			<div id="footer">
				<?php include 'includes/footer.php'; ?>
			</div>
			
		</div>		
		
	</body>
	
</html>