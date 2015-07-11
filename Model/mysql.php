<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/constante.php');

function print_error($msg) {
	echo $msg.'<br/>';
}

class mysqlObject {
	private static $bdd = -1;
	
/*	public function __construct($host, $db_name, $login, $mdp) {
		connect($host, $db_name, $login, $mdp);
	}
	*/	
	public function __construct($host, $db_name, $login, $mdp) {
		$this->connect($host, $db_name, $login, $mdp);
	}
		
	// Fonction se connectant a la base de donnee.
	public function connect($host, $db_name, $login, $mdp) {
		
		try {
			$this->bdd = new PDO('mysql:host='.$host.';dbname='.$db_name, $login, $mdp);
		} catch (Exception $e){
			$this->bdd = -1;
			die('Erreur : ' . $e->getMessage());
		}
	}
	
	public function isConnected(){
		return $this->bdd != -1;
	}
		
	// Fonction qui permet de faire un select *
	// Param : table = Nom de table
	// 		   	cols = Array contenant la liste des colonnes a selectinner dans l'ordre
	//     conditions = Array contenant la liste des conditons	
	// Sortie : Retourne in array d'objet contenant 		
	public function select($req) {
		$res = $this->bdd->query($req);
		$array_res = array();
				
		while($donne = $res->fetchObject()) {
			array_push($array_res, $donne);
		}
				
		return $array_res;
	}

	public function execQuery($req) {
		return $this->bdd->exec($req);
	}

	
	public function close() {
		$this->bdd = -1;
	}
}

?>