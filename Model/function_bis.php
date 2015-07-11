<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/constante.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/model/mysql.php');


function get_user($login, $mdp) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$donne = $bd->select('select * from compte where login = "'.$login.'" AND pwd = "'.$mdp.'";');
	
	$bd->close();
	return $donne;		
}

function get_user_by_login($login) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$donne = $bd->select('select * from compte where login = "'.$login.'";');
	
	$bd->close();
	return $donne;	
}

function get_user_by_id($id) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$donne = $bd->select('select * from compte where ID_USER = "'.$id.'";');
	
	$bd->close();
	return $donne;	
}


function get_all_sondages($order = 'date_creation desc') {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$donne = $bd->select('select * from sondage order by '.$order.';');
	
	$bd->close();
	return $donne;
}

// Retourne tout les sondages d'un utilisateur
function get_user_sondages($id, $order = 'date_creation desc') {	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$donne = $bd->select('select * from sondage where id_user = '.$id.' order by '.$order.';');

	$bd->close();	
	return $donne;		
}


// Returne tout les sondages ouverts
function get_all_opened_sondages($order = 'date_creation desc') {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$donne = $bd->select('select * from sondage where status = '.OPEN.' order by '.$order.';');
	
	$bd->close();
	return $donne;
}

function get_sondage_questions($id, $order = 'num asc') {
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$donne = $bd->select('select * from question where id_sondage = '.$id.' order by '.$order.';');
	
	$bd->close();
	return $donne;
}

function get_question_reponses($id, $order = 'num asc') {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$donne = $bd->select('select * from reponse where id_question = '.$id.' order by '.$order.';');
	
	$bd->close();
	return $donne;
}


function get_question_type($id) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$rep = $bd->select('select * from reponse where id_question = '.$id.'');
	
	if(count($rep) == 1) {
		return $rep[0]->TYPE;
	}
	return TYPE_CHOIX_MULTIPLE;
}


?>