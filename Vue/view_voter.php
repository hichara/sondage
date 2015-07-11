<?php


include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function.php');


function affichage_voter() {
	$ok = isset($_GET[NUM]) && isset($_GET[NUM2]);
	
	if($ok) {
		$ok = is_numeric($_GET[NUM]) && is_numeric($_GET[NUM2]);
	}
	
	if($ok) {
		afficher_question_for_voter($_GET[NUM], $_GET[NUM2]);
	} else {
		afficher_liste_opened_sondage();
	}
}

function afficher_liste_opened_sondage() {
	$sondages = get_all_opened_sondages();
	
	echo '<div class="panel panel-primary">';
	echo '<div class="panel-heading">';
		echo '<h3>Liste des sondages</h3>';
	echo '</div>';
	echo '<div class="list-group scrollable-menu">';
	foreach($sondages as $s) {
		echo '<a class="list-group-item" href="'.ROOT.'?num='.$s->ID_SONDAGE.'&num2=1">';
			echo $s->LIBELE;
		echo '</a>';
	}
	echo '</div>';
}

function afficher_reponse_by_type($r) {
	if($r->TYPE == TYPE_CHOIX_MULTIPLE) {
		echo '<input type="radio" name="'.REPONSE.'" value="'.$r->NUM.'">';
		echo '&nbsp;&nbsp;'.$r->LIBELE;
	} else {
		$min = $r->MIN;
		$max = $r->MAX;
		echo '<span>De '.$min.' à </span>';
		echo '<span> '.$max.' </span>';
		echo '<input type="number" name="'.REPONSE.'" min="'.$min.'" max="'.$max.'" value="0">';
	}
}

function afficher_liste_reponse_for_voter($q) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);

	$reponse = $bd->select('select * from reponse where 
				id_question = '.$q->ID_QUESTION.';');

	if(count($reponse) == 0) {
		echo '<div class="list-group">';
			echo '<div class="list-group-item list-grou-warning">';
				echo "Aucune reponse pour cette question";
			echo '</div>';
			echo '<div class="list-group-item list-grou-warning">';
				echo '<button> Retour liste sondage</button>';
			echo '</div>';
		echo '</div>';
	} else if(isset($_COOKIE[COOKIE_QUESTION.$q->ID_QUESTION])) {
		if(count($reponse) > $q->NUM) {
			$button_next = '<a href="'.ROOT.'?num='.$_GET[NUM].'&num2='.($_GET[NUM2] + 1).'">
			<button class="btn btn-primary btn-block">Next</button></a>';
		} else {
			$button_next = '<a href="'.ROOT.'">
			<button class="btn btn-primary btn-block">Retour liste sondage</button></a>';
		}
		echo '<div class="list-group">';
			echo '<div class="list-group-item list-group-item-warning">';
				echo "Vous avez répondu ". 
				$_COOKIE[COOKIE_QUESTION.$q->ID_QUESTION]." a cette question";
			echo '</div class="list-group-item">';
				echo  $button_next;
			echo '</div>';
			echo '<div>';
		echo '</div>';
	} else {
		echo '<div class="list-group">';
		echo '<form action="" method="POST">';
		   echo '<input type="hidden" name="'.ACTION.'" value="'.VOTER.'"">';
		   echo '<input type="hidden" name="'.ID.'" value="'.$q->ID_QUESTION.'">';
			foreach($reponse as $r) {
				echo '<div class="list-group-item">';
				  afficher_reponse_by_type($r);
				echo '</div>';
			}
			echo '<button class="btn btn-primary btn-block">
				 Valider</button>';
		echo '</form>';
		echo '</div>';
	}
}

function afficher_question_for_voter($id_s, $num_q) {
	$bd = new mysqlObject(HOST, DB, USER, MDP);
	
	$sondage = $bd->select('select * from sondage where id_sondage = '.$id_s.';');
	
	$question = $bd->select('select * from question where id_sondage = '.$id_s.' and num = '.$num_q.';');
	
	if(count($sondage) == 0) {
		$sondage_label = 'Erreur sondage inconnu';
	} else {
		$sondage_label = $sondage[0]->LIBELE;		
	}
	
	if(count($question) == 0) {
		$question_label = 'Erreur question inconnue';		
	} else {
		$question_label = $question[0]->LIBELE;				
	}
	?>
    
    <div class="panel panel-primary">
    	<div class="panel-heading">
        	<h4><?php echo $sondage_label; ?></h4>
        </div>
        <?php
			if(count($question) == 0) {
				echo '<div class="panel panel-body">
				    '.$question_label.'
					</div>';				
			} else {
				afficher_liste_reponse_for_voter($question[0]);
			}
		?>
    </div>
	
	<?php
}

?>



