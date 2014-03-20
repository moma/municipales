<?php
set_time_limit(1500);

include_once 'fonctions/accesBD.php';

$link = Connection();

$lignes = file("donnees/mun1-2008-tt.txt",FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

for($i=1;$i<sizeof($lignes);$i++){
	$champs = explode(' ',$lignes[$i]);
	$champ0 = explode('.',$champs[0]);
	$sql_string = "SELECT * FROM e_commune WHERE code_1='%s' AND code_2='%s'";		
	$sql = sprintf($sql_string,Quote_smart($champ0[0],$link),Quote_smart($champ0[1],$link));
	$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
	if($commune = mysqli_fetch_array($req)){	
		//echo "WARNING : ".$commune['pk_commune']." - ".$lignes[$i]."<br />";
		$sql_string = "UPDATE e_commune SET superficie='%s',info_1='%s',info_2='%s',ville='%s' WHERE pk_commune=%s";	
		$sql = sprintf($sql_string,Quote_smart($champs[1],$link),Quote_smart($champs[2],$link),Quote_smart($champs[3],$link),Quote_smart($champs[7],$link),$commune['pk_commune']);
		mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
	} else {
		echo "NEW : ".$lignes[$i]."<br />";
		$sql_string = "INSERT INTO e_commune (fk_pays,code_1,code_2,superficie,info_1,info_2,ville)";	
		$sql_string .= " VALUES (1,'%s','%s','%s','%s','%s','%s')";
		$sql = sprintf($sql_string,Quote_smart($champ0[0],$link),Quote_smart($champ0[1],$link),Quote_smart($champs[1],$link),Quote_smart($champs[2],$link),Quote_smart($champs[3],$link),Quote_smart($champs[7],$link));
		mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
	}
}

echo sizeof($lignes);

Deconnection($link);

echo "<br />END";

?>