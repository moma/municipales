<?php
include_once 'includes/header_php.php';

include_once 'fonctions/accesBD.php';

$link = Connection();

if(isset($_REQUEST['submit_form_recherche'])){
	$_SESSION['pk_commune'] = $_REQUEST['pk_commune'];	
}

if(!isset($_SESSION['pk_commune'])) $_SESSION['pk_commune'] = 1;

$sql = "SELECT * FROM e_commune,e_resultat,e_election,e_type_election ";
$sql .= "WHERE fk_lieu=pk_commune AND fk_election=pk_election AND fk_type_election=pk_type_election ";
$sql .= "AND pk_commune=".Quote_smart($_SESSION['pk_commune'],$link)." ORDER BY pk_type_election,date_election";		
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
while($res = mysqli_fetch_array($req)){
	$liste_res[] = $res;
}

if(isset($liste_res[0])) $_SESSION['commune'] =  $liste_res[0]['ville'];

$sql_string = "SELECT * FROM e_commune,e_pays WHERE fk_pays=pk_pays ORDER BY ville";	
$sql = sprintf($sql_string);
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_commune = array();
while($commune = mysqli_fetch_array($req)){
	$liste_commune[] = $commune;
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
		
	</head> 

	<body>		
		
		<div id="container">
		
			<div id="header">
				<?php include 'includes/header.php'; ?>
			</div>					
			
			<form id="form_recherche" name="form_recherche" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="select-commune">Commune:</label>
			<select id="pk_commune" name="pk_commune">				
				<?php for($i=0;$i<sizeof($liste_commune);$i++){ ?>				
				<option value="<?php echo $liste_commune[$i]['pk_commune']; ?>" <?php if($liste_commune[$i]['pk_commune']==$_SESSION['pk_commune']) echo 'selected="selected"'; ?>><?php echo $liste_commune[$i]['ville']; ?></option>
				<?php } ?>						
			</select>
			<input id="submit_form_recherche" name="submit_form_recherche" type="submit" value="RECHERCHER" />				
			</form>
			
			<br />
			
			<table>
				<tr>
					<th>TYPE ELECTION</th>
					<th>DATE ELECTION</th>
					<th>NB INSCRITS</th>
					<th>NB VOTANTS</th>
					<th>NB BULLETINS EXPRIMES</th>
					<th>TAU</th>
					<th>TAU NORMALISE</th>
					<th>DIFF</th>
				</tr>
				<?php for($i=0;$i<sizeof($liste_res);$i++){ 
				$tau = round(log($liste_res[$i]['nb_votants']/($liste_res[$i]['nb_inscrits']-$liste_res[$i]['nb_votants'])),2);
				$tau_normalise = round(($liste_res[$i]['param_1']*log($liste_res[$i]['nb_inscrits']))+$liste_res[$i]['param_2'],2);
				?>
				<tr>
					<td><?php echo $liste_res[$i]['type_election']; ?></td>
					<td><?php echo $liste_res[$i]['date_election']; ?></td>
					<td><?php echo $liste_res[$i]['nb_inscrits']; ?></td>
					<td><?php echo $liste_res[$i]['nb_votants']; ?></td>
					<td><?php echo $liste_res[$i]['nb_bulletins_exprimes']; ?></td>
					<td><?php echo $tau; ?></td>
					<td><?php echo $tau_normalise; ?></td>
					<td><?php echo $tau-$tau_normalise; ?></td>
				</tr>
				<?php } ?>
			</table>
			
			<div id="footer">
				<?php include 'includes/footer.php'; ?>
			</div>
			
		</div>				
				
	</body>
	
</html>