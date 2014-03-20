<?php
set_time_limit(1500);

include_once 'fonctions/accesBD.php';

/**
 * linear regression function
 * @param $x array x-coords
 * @param $y array y-coords
 * @returns array() m=>slope, b=>intercept
 */
function linear_regression($x, $y) {

  // calculate number points
  $n = count($x);
  
  // ensure both arrays of points are the same size
  if ($n != count($y)) {

    trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
  
  }

  // calculate sums
  $x_sum = array_sum($x);
  $y_sum = array_sum($y);

  $xx_sum = 0;
  $xy_sum = 0;
  
  for($i = 0; $i < $n; $i++) {
  
    $xy_sum+=($x[$i]*$y[$i]);
    $xx_sum+=($x[$i]*$x[$i]);
    
  }
  
  // calculate slope
  $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
  
  // calculate intercept
  $b = ($y_sum - ($m * $x_sum)) / $n;
    
  // return result
  return array("m"=>$m, "b"=>$b);

}

$link = Connection();

$sql = "SELECT x,y FROM e_serie WHERE fk_type_serie=2 ORDER BY x";	
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
$liste_x = array();
$liste_y = array();
while($res = mysqli_fetch_array($req)){
	$liste_x[] = log($res['x']);
	$liste_y[] = $res['y'];
}

echo var_dump(linear_regression($liste_x,$liste_y))."<br /><br />";

$sql = "SELECT x,y FROM e_serie WHERE fk_type_serie=2 AND x>=100 AND x<=10000 ORDER BY x";	
$req = mysqli_query($link,$sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysqli_error($link));
$liste_res = array();
$liste_x = array();
$liste_y = array();
while($res = mysqli_fetch_array($req)){
	$liste_x[] = log($res['x']);
	$liste_y[] = $res['y'];
}

echo var_dump(linear_regression($liste_x,$liste_y));

Deconnection($link);


?>