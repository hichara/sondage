<?php


include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/mysql.php');


$bd = new mysqlObject(HOST, DB, USER, MDP);

$id_q = $_GET['ID'];
$name = "";

$val = $bd->select('select valeur, count(valeur) as c from statistique 
					where id_question = '.$id_q.'
					group by valeur;');

$q = $bd->select('select * from 
		question where id_question = '.$id_q.';');

$name = $q[0]->LIBELE;
		
$datas = "";

foreach($val as $v) {
	if($datas == "") {
		$datas = '["'.$v->valeur.'", '.$v->c.']';
	} else {
		$datas .= ',["'.$v->valeur.'", '.$v->c.']';
	}
}

$series = '"series" : [{
	"type": "pie",
	"name": "stat question",
	"data": ['.$datas.'] 
}]';

echo '{
	"title" : {
		"text" : "'.$name.'"
	},
	'.$series.'
}';

?>