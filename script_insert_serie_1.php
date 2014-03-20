<?php
set_time_limit(1500);

include_once 'fonctions/accesBD.php';

$link = Connection();

$sql = "SELECT * FROM e_resultat WHERE fk_election=1 ORDER BY nb_inscrits";	
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
$nb = 0;
$nb_res = 1;
$index_res = 0;
$somme_nb_inscrits = 0; 
$somme_nb_votants = 0;
$sample_size = 200;
while($res = mysqli_fetch_array($req)){
	$somme_nb_inscrits += $res['nb_inscrits']; 
	$somme_nb_votants += $res['nb_votants']; 
	if($nb_res%$sample_size == 0){
		if(($somme_nb_inscrits/$sample_size)-($somme_nb_votants/$sample_size)){
			$liste_res[$index_res]['nb_inscrits_moy'] = $somme_nb_inscrits/$sample_size;
			$liste_res[$index_res]['tau_moy'] = log(($somme_nb_votants/$sample_size) / (($somme_nb_inscrits/$sample_size)-($somme_nb_votants/$sample_size)));
			$sql_string = "INSERT INTO e_serie (fk_type_serie,x,y) VALUES (1,'%s','%s')";			
			$sql = sprintf($sql_string,$liste_res[$index_res]['nb_inscrits_moy'],$liste_res[$index_res]['tau_moy']);
			mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
			$index_res++;
		}
		$somme_nb_inscrits = 0; 
		$somme_nb_votants = 0;
	}
	$nb_res++;
}

echo sizeof($liste_res);

Deconnection($link);

echo "<br />END";

?>