<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/mysql.php');


$bd = new mysqlObject(HOST, DB, USER, MDP);

$id_q = $_GET['ID'];

$id_name = "name";

$res = $bd->select('select * from reponse where id_question = '.$id_q.';');
$val = $bd->select('select valeur, count(valeur) as c from statistique 
					where id_question = '.$id_q.'
					group by valeur;');

$q = $bd->select('select * from 
		question where id_question = '.$id_q.';');

if(count($q) != 0) {

$table;
foreach($res as $r) {
	$table[$r->NUM]["label"] = $r->LIBELE;
	$table[$r->NUM]["nb"] = 0;
}

foreach($val as $v) {
	$table[$v->valeur]["nb"] = $v->c;
}


$serie = "";
foreach($res as $r) {
	if($serie == "") {
		$serie = '{
			"name" : "'.$table[$r->NUM]["label"].'",
			"data" : ['.$table[$r->NUM]["nb"].']
		} '; 
	} else {
		$serie .= ',{
			"name" : "'.$table[$r->NUM]["label"].'",
			"data" : ['.$table[$r->NUM]["nb"].']
		}'; 		
	}
}

$serie = '"series" : [ '.$serie.' ]';

$name = $q[0]->LIBELE;

echo '{
	  "title" : {
		  "text" : "'.$name.'"
	  },
	  '.$serie.'
	}';

} else {
	echo  '{}';
}
 
?>

