<?php
// Contient les fonctions contollant les action du site
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/constante.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function_bis.php');




function connection($nom, $mdp) {
	$_nom = htmlspecialchars($nom);
	$_mdp = htmlspecialchars($mdp);
	$_mdp = md5($_mdp);
	 
	$res = get_user($_nom, $_mdp);
	
	if(count($res) != 1) {
		echo count($res);

		return "ERREUR CONNECTION";
	}
		
	$_SESSION[CONNECTED] = TRUE;	
	$_SESSION[ID_USER] = $res[0]->ID_USER;
	$_SESSION[LOGIN] = $res[0]->LOGIN;
	$_SESSION[USER_TYPE] = $res[0]->STATUS;
	
	return 1;
}


function inscription($nom, $mdp, $mdpBIS, $status = USER_NUM) {
	if($mdp != $mdpBIS) {
		return ERROR_NOT_SAME_VALUE;
	}

	$_nom = htmlspecialchars($nom);
	$_mdp = htmlspecialchars($mdp);

	$_nom = htmlspecialchars($nom);
	$_mdp = htmlspecialchars($mdp);
	$_mdp = md5($_mdp);
	
	$res = get_user_by_login($_nom);
		
	if(count($res) != 0) {
		return ERROR_EXISTING_USER;
	}

	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$req = 'insert into compte 
	values(null, "'.$_nom.'", "'.$_mdp.'",
	 '.$status.', '.time().');';
		
	return $bd->execQuery($req);
}

function connection_as_voter() {
	echo 'ussss<br/>';
	$_SESSION[CONNECTED] = TRUE;
	$_SESSION[USER_TYPE] = VOTER_NUM;	
	$_SESSION[ID_USER] = 0;
}

function vote($id_q, $val) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if($_SESSION[USER_TYPE] != VOTER_NUM) {
		return ERROR_CORRUPTED_DATA;
	}
	
	if(isset($_COOKIE[COOKIE_QUESTION.$id_])) {
		return ERROR_CORRUPTED_DATA;
	}
	
	if(!is_numeric($id_q) || !is_numeric($val)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$reponse = $bd->select('select * from reponse
			  where id_question = '.$id_q.';');
	
	if(count($reponse) == 0) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$ok = false;
	if(count($reponse) == 1) {
		$ok = ($reponse[0]->TYPE == TYPE_CHOIX_MULTIPLE)
				&& ($reponse[0]->NUM == $val);
				
		$ok = $ok || (($reponse[0]->TYPE == TYPE_INTERVAL)
				&& ($reponse[0]->MIN <= $val && $val <= $reponse[0]->MAX));
	}
	
	if($ok) {
		$req = 'insert into statistique 
			values('.$id_q.', '.$val.', '.time().')';
		setcookie(COOKIE_QUESTION.$id_q, $val.'', time() + COOKIE_LIFE_SPAN);
		
		return $bd->execQuery($req);
	} else {
		
	}
	
	foreach($reponse as $r) {
		if($r->NUM == $val) {
			$req = 'insert into statistique 
				values('.$id_q.', '.$val.', '.time().')';
		setcookie(COOKIE_QUESTION.$id_q, $val.'', time() + COOKIE_LIFE_SPAN);

			return $bd->execQuery($req);
		}
	}
	
	return ERROR_CORRUPTED_DATA;
}

function add_sondage($label) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(strlen($label) > LIMIT_LIBELE) {
		return ERROR_STR_OUT_OF_LIMIT;
	}
	
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$req = 'insert into sondage values(null,
		'.$_SESSION[ID_USER].',
		"'.$label.'",  
		'.time().', 
		'.OPEN.');';

	return $bd->execQuery($req);
}


function add_question($id_s, $question) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(strlen($question) > LIMIT_LIBELE) {
		return ERROR_STR_OUT_OF_LIMIT;
	}
	
	if(!is_numeric($id_s)) {
		return ERROR_CORRUPTED_DATA;
	}

	$bd = new mysqlObject(HOST, DB, USER, MDP);
		
	$sondage = $bd->select('select * from sondage
		where id_sondage = '.$id_s.' 
		AND id_user = '.$_SESSION[ID_USER].';');
		
	if(count($sondage) == 0) {
		return ERROR_NOT_YOUR_SONDAGE;
	}
	
	$questions = $bd->select('select * from question
		where id_sondage = '.$id_s.';');
		
	$count = count($questions) + 1;
	
	if($count > LIMIT_NB_QUESTION) {
		return ERROR_LIMIT_NB_QUESTION;
	}
	$label = htmlspecialchars($question);
	
	$req = 'insert into question values(null,
		 '.$id_s.', "'.$label.'", '.$count.');';
		 
	return $bd->execQuery($req);	
}

function add_reponse($id_q, $val = "", $min = 0, $max = 0) {
	
	if(strlen($val) > LIMIT_LIBELE_REPONSE) {
		return ERROR_STR_OUT_OF_LIMIT;
	}
	
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(!is_numeric($id_q)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$question = $bd->select('
		select * from question
		where id_question = '.$id_q.';');

	if(count($question) == 0) {
		return ERROR_NOT_YOUR_QUESTION;
	}
	
	$sondage = $bd->select(
		'select * from sondage 
		where id_user = '.$_SESSION[ID_USER].';');
		
	$ok = false;
	foreach($sondage as $s){
		if($question[0]->ID_SONDAGE 
			== $s->ID_SONDAGE) {
			$ok = true;
			break;
		}
	}
	
	if(!$ok) {
		return ERROR_NOT_YOUR_SONDAGE;
	}
	
	$reponses = $bd->select('
		select * from reponse
		where id_question = '.$id_q.';');
			
	$count = count($reponses) + 1;
	
	if($count > LIMIT_NB_REPONSE) {
		return ERROR_LIMIT_NB_REPONSE;
	}
	
	$label = "";
	if($val != "" && $val != NULL) {
		$type_rep = TYPE_CHOIX_MULTIPLE;
		$label = htmlspecialchars($val);
	} else {
		if(!is_numeric($min) &&!is_numeric($max)
		|| $min >= $max) {
			return ERROR_CORRUPTED_DATA;
		}
		$type_rep = TYPE_INTERVAL;		
	}
	
	if(count($reponses) != 0 && $type_rep == TYPE_INTERVAL) {
		return ERROR_INTERVAL_TYPE_ALREADY_PROVIDED;
	}
		
	$req = 'insert into reponse
		values (null, '.$id_q.', '.$count.',
		'.$type_rep.', "'.$label.'", '.$min.', '.$max.');';
	
	return $bd->execQuery($req);
}

function swap_question($id_s, $num1, $num2) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(!is_numeric($num1) || !is_numeric($num2)) {
		return ERROR_CORRUPTED_DATA;
	}

	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$q1 = $bd->select('select Q.ID_QUESTION as id
					  from question as Q, sondage as S
					  where Q.num = '.$num1.'
					  and Q.id_sondage = '.$id_s.'
					  and S.id_user = '.$_SESSION[ID_USER].'
					  and Q.id_sondage = S.id_sondage');
	
	$q2 = $bd->select('select Q.ID_QUESTION as id
					  from question as Q, sondage as S
					  where Q.num = '.$num2.' 
					  and Q.id_sondage = '.$id_s.'
					  and S.id_user = '.$_SESSION[ID_USER].'
					  and Q.id_sondage = S.id_sondage');
					  		
	$count1 = count($q1);
	$count2 = count($q2);
	
	if($count1 != 1 || $count2 != 1) {
		return ERROR_NOT_YOUR_SONDAGE;
	}
	
	if($q1[0]->id == NULL || $q1[0] == "") {
		return ERROR_NOT_YOUR_QUESTION;
	}

	if($q2[0]->id == NULL || $q2[0] == "") {
		return ERROR_NOT_YOUR_QUESTION;
	}
		
	$req1 = 'update question set num = '.$num2.' 
		where id_question = '.$q1[0]->id.';';

	$req2 = 'update question set num = '.$num1.'
		where id_question = '.$q2[0]->id.';';
		
	return $bd->execQuery($req1) == $bd->execQuery($req2);
}

function swap_reponse($id_q, $num1, $num2) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(!is_numeric($num1) || !is_numeric($num2) || !is_numeric($id_q)) {
		return ERROR_CORRUPTED_DATA;
	}

	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$r1 = $bd->select('select R.ID_REPONSE as id
					   from reponse as R, question as Q, sondage as S
					   where R.num = '.$num1.'
					   and R.id_question = '.$id_q.'
					   and R.id_question = Q.id_question
					   and Q.id_sondage = S.id_sondage
					   and S.id_user = '.$_SESSION[ID_USER].';');

	$r2 = $bd->select('select R.ID_REPONSE as id
					   from reponse as R, question as Q, sondage as S
					   where R.num = '.$num2.'
					   and R.id_question = '.$id_q.'
					   and R.id_question = Q.id_question
					   and Q.id_sondage = S.id_sondage
					   and S.id_user = '.$_SESSION[ID_USER].';');
	
	
	$count1 = count($r1);
	$count2 = count($r2);
	
	if($count1 != 1 || $count2 != 1) {
		return ERROR_NOT_YOUR_QUESTION;
	}
	
	if($r1[0]->id == NULL || $r1[0] == "") {
		return ERROR_NOT_YOUR_QUESTION;
	}

	if($r2[0]->id == NULL || $r2[0] == "") {
		return ERROR_NOT_YOUR_QUESTION;
	}
	
	$req1 = 'update reponse set num = '.$num2.' 
		where id_reponse = '.$r1[0]->id.'';
		
	$req2 = 'update reponse set num = '.$num1.'
		where id_reponse = '.$r2[0]->id.'';
		
	return $bd->execQuery($req1) == $bd->execQuery($req2);
}


function delete_reponse($id) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(!is_numeric($id)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$r = $bd->select('select * from reponse
		where id_reponse = '.$id.';'); 
	
	if(count($r) != 1) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$q = $bd->select('select * from question
		where id_question = '.$r[0]->ID_QUESTION.'');

	if(count($q) != 1) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$s = $bd->select('select * from sondage
		where id_sondage = '.$q[0]->ID_SONDAGE.'
		and id_user = '.$_SESSION[ID_USER].';');

	if(count($s) != 1) {
		return ERROR_NOT_YOUR_REPONSE;
	}
	
	$req1 = 'delete from reponse where id_reponse = '.$id.';';
	
	$req2 = 'update reponse set num = num - 1 
			where num > '.$r[0]->NUM.' AND 
			id_question = '.$q[0]->ID_QUESTION.';';  
	
	return $bd->execQuery($req1) && $bd->execQuery($req2);
}

function delete_question($id) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$q = $bd->select('select * from question
		where id_question = '.$id.';');
	
	if(count($q) != 1) {
		return ERROR_NOT_YOUR_QUESTION;
	}
	
	$s = $bd->select('select * from sondage
		where id_sondage = '.$q[0]->ID_SONDAGE.'
		AND id_user = '.$_SESSION[ID_USER].';');

	if(count($s) != 1) {
		return ERROR_NOT_YOUR_QUESTION;
	}
	
	$req1 = 'delete from question where id_question = '.$id.';';
	$req2 = 'delete from reponse where id_question = '.$id.';';

	$req3 = 'update question set num = num - 1 
			where num > '.$q[0]->NUM.' AND 
			id_sondage = '.$s[0]->ID_SONDAGE.';';  
	
	return $bd->execQuery($req1) &&
		   $bd->execQuery($req2) &&
		   $bd->execQuery($req3);
}

// Supprime un sondage en tant que admin : Ne fais pas de verification
// de propriété
function delete_sondage_as_admin($id) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if($_SESSION[USER_TYPE] != ADMIN_NUM) {
		return ERROR_OPERATION_FORBIDDEN;
	}
		
	if(!is_numeric($id)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$q = $bd->select('select * from question where id_sondage = '.$id.';');
	
	foreach($q as $val) {
		$bd->execQuery('delete from reponse 
			where id_question = '.$val->ID_QUESTION.';');
		
		$bd->execQuery('delete from question
			where id_question = '.$val->ID_QUESTION.';');
	}
	
	return $bd->execQuery('delete from sondage where id_sondage = '.$id.';');
}

// Supprime un compte en tant qu'admin
function delete_compte_as_admin($id, $allow_admin_del = false) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if($_SESSION[USER_TYPE] != ADMIN_NUM) {
		return ERROR_OPERATION_FORBIDDEN;
	}
		
	if(!is_numeric($id)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$stat = ADMIN_NUM;
	if($allow_admin_del) {
		$stat = -1;
	}	
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$req1 = 'delete from compte where id_user = '.$id.' AND status != '.$stat.';';
	
	$bd->execQuery($req1);
	$sondages = $bd->select('select * from sondage where id_user = '.$id.';');
	
	foreach($sondages as $s) {
		delete_sondage_as_admin($s->ID_SONDAGE);
	}
	return 1;
}

function delete_sondage($id) {
	
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if($_SESSION[USER_TYPE] == ADMIN_NUM) {
		return delete_sondage_as_admin($id);
	}
		
	if(!is_numeric($id)) {
		return ERROR_CORRUPTED_DATA;
	}
		
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$s = $bd->select('select * from sondage
	 where id_sondage = '.$id.'
	 AND id_user = '.$_SESSION[ID_USER].';');
	
	if(count($s) != 1) {
		return ERROR_NOT_YOUR_SONDAGE;
	}
	
	$q = $bd->select('select * from question where id_sondage = '.$id.';');
	
	foreach($q as $val) {
		$bd->execQuery('delete from reponse 
			where id_question = '.$val->ID_QUESTION.';');
		
		$bd->execQuery('delete from question
			where id_question = '.$val->ID_QUESTION.';');
	}
	
	return $bd->execQuery('delete from sondage where id_sondage = '.$id.';');
}

function open_close_sondage($id) {
	if(!isset($_SESSION[CONNECTED])) {
		return ERROR_NOT_CONNECTED;
	}
	
	if(!is_numeric($id)) {
		return ERROR_CORRUPTED_DATA;
	}
	
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$req = 'select * from sondage
			where id_sondage = '.$id.' and
			id_user = '.$_SESSION[ID_USER].';';
	
	$s = $bd->select($req);	 
	
	if(count($s) != 1) {
		return ERROR_NOT_YOUR_SONDAGE;
	}
	
	if($s[0]->STATUS == OPEN) {
		$stat = CLOSE;
	} else {
		$stat = OPEN;
	}
	
	$req = 'update sondage set status = '.$stat.' 
			where id_sondage = '.$s[0]->ID_SONDAGE.';';
			
	return $bd->execQuery($req);
}


function disconnection() {
	session_destroy();
}


	
	



?>