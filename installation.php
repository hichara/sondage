<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/bddInfo.php');

$link = mysql_connect(HOST, USER, MDP);
if (!$link) {
    die('Connexion impossible : ' . mysql_error());
	echo '<br/>';
}

$sql = 'CREATE DATABASE '.DB.'';
if (mysql_query($sql, $link)) {
    echo "Base de données créée correctement<br/>";
} else {
    echo 'Erreur lors de la création de la base de données : ' . mysql_error() . "<br/>";
}


if (!mysql_select_db(DB, $link)) {
    echo 'Sélection de base de données impossible';
	echo '<br/>';
    exit;
}

$req1 = 'create table compte(ID_USER int AUTO_INCREMENT, 
			LOGIN varchar(50),
			PWD varchar(200),
			STATUS INT,
			DATE_INSCRIPTION INT,
			PRIMARY KEY(ID_USER));';

$req2 = 'create table question(ID_QUESTION INT AUTO_INCREMENT,
		    ID_SONDAGE INT,
			LIBELE varchar(150),
			NUM INT,
			PRIMARY KEY(ID_QUESTION));';
			
$req3 = 'create table reponse(ID_REPONSE INT AUTO_INCREMENT,
			ID_QUESTION INT,
			NUM INT,
			TYPE INT,
			LIBELE varchar(30),
			MIN INT,
			MAX INT,
			PRIMARY KEY(ID_REPONSE));';			

$req4 = 'create table sondage(ID_SONDAGE INT AUTO_INCREMENT,
			ID_USER INT,
			LIBELE varchar(80),
			DATE_CREATION INT,
			STATUS INT,
			PRIMARY KEY (ID_SONDAGE));';
			
$req5 = 'create table statistique(ID_QUESTION INT,
			VALEUR INT,
			date_reponse INT);';

$req6 = 'insert into compte values(null, "'.LOGIN_ADMIN.'", "'.md5(MDP_ADMIN).'", "'.ADMIN_NUM.'", '.time().')';

$table = array($req1, $req2, $req3, $req4, $req5, $req6);

foreach($table as $req) {
	$result = mysql_query($req, $link);
	
	if (!$result) {
	    $message  = 'Requête invalide : ' . mysql_error() . "\n";
   		$message .= 'Requête complète : ' . $req;
    	die($message);
	}
}

mysql_close($link);
?>