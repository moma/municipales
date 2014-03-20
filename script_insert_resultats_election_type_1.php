<?php
set_time_limit(1500);

include_once 'fonctions/accesBD.php';

$pk_election = 2;

$link = Connection();

$lignes = file("donnees/mun1-2008-tt.txt",FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

for($i=1;$i<sizeof($lignes);$i++){
	$champs = explode(' ',$lignes[$i]);
	$champ0 = explode('.',$champs[0]);
	$sql_string = "SELECT * FROM e_commune WHERE code_1='%s' AND code_2='%s'";		
	$sql = sprintf($sql_string,Quote_smart($champ0[0],$link),Quote_smart($champ0[1],$link));
	$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
	if($commune = mysqli_fetch_array($req)){		
		$sql_string = "INSERT INTO e_resultat (fk_election,fk_lieu,nb_inscrits,nb_votants,nb_bulletins_exprimes) VALUES (%s,%s,'%s','%s','%s') ";		
		$sql = sprintf($sql_string,$pk_election,$commune['pk_commune'],Quote_smart($champs[4],$link),Quote_smart($champs[5],$link),Quote_smart($champs[6],$link));
		mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
	} else {
		echo "ERREUR : COMMUNE INCONNUE : ".$lignes[$i]."<br />";
	}
}

echo sizeof($lignes);

Deconnection($link);

echo "<br />END";

?>