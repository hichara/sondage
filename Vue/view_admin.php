<style>
.scrollable-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
}

</style>

<?php


include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function.php');


function affichage_admin() {
?>

<div class="jumbotron">
    <h1>Mini projet web : sondage</h1> 
    <p>Vous êtes connect&eacute; en tant qu'admin</p>
    <a href="http://localhost/sondage/index.php?action=logout" class="btn btn-danger btn-lg">Deconnection</a>
  </div>
<?php

	afficher_users();
	afficher_sondages();
}

function afficher_sondages() {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$sondage = $bd->select('select * from sondage order by date_creation desc');
	echo '<div class="panel panel-info">';
	echo '<div class="panel-heading">';
		echo '<h3> Liste des sondages </h3>';
	echo '</div>';
	echo '<div class="list-group scrollable-menu">';
	foreach($sondage as $s) {
		if($s->STATUS == OPEN) {
			echo '<div class="list-group-item"> ';
		} else {
			echo '<div class="list-group-item list-group-item-danger"> ';			
		}
			echo $s->LIBELE;
			echo '<a href="'.ROOT.'?action='.DELETE_SONDAGE.'&id='.$s->ID_SONDAGE.'" class="pull-right need_confirm">
					<button class="badge">Supprimé</button></a>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';	
}

function afficher_users() {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$user = $bd->select('select * from compte 
			where status != '.ADMIN_NUM.';');
	echo '<div class="panel panel-primary">';
	echo '<div class="panel-heading">';
		echo '<h3> Liste utilisateur </h3>';
	echo '</div>';
	echo '<div class="list-group scrollable-menu">';
	foreach($user as $u) {
		echo '<div class="list-group-item"> ';
			echo $u->LOGIN;
			echo '<a href="'.ROOT.'?action='.DELETE_USER.'&id='.$u->ID_USER.'" class="pull-right need_confirm">
				<button class="badge">Supprimé</button></a>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
}

?>