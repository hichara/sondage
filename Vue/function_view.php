<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/constante.php');



function afficher_ajout_question($s) {
	echo '<div>';
		echo '<input type="text" lenghmaxlength="80" size="80">';
		echo '<button>'.AJOUTER.'</button>';
	echo '</div>';
}


function afficher_ajout_reponse($q) {
	echo '<div>';
		echo '<span>';
			echo '<input id="add_rep_q" type="text">';		
		echo '<span>';
		echo '<input type="radio" name="'.TYPE_REPONSE.'" value="'.INTERVAL.'"><label>'. INTERVAL .'</label>';
		echo '<input type="radio" name="'.TYPE_REPONSE.'" value="'.LITTERAL.'"><label>'.LITTERAL.'</label>';
		echo '<input type="hidden" name="'.QUESTION_TARGET.'" value="'.$q->id.'"/>';
	echo '</div>';
}

function afficher_question_reponses($q) {
	$reps = get_question_reponses($q->id_question);
	
	foreach($reps as $val) {
		echo '<div>';
			afficher_reponse_par_type($val);
		echo '</div>';		
	}
}

function afficher_reponse_par_type($rep) {
	if($rep->type == LITTERAL) {
		echo '<input type="radio" name="'.REPONSE.'_'.$rep->id_question.'"/>';
	} else if($rep-type == RANGE_INT) {
		echo '<input type="number" name="'.REPONSE.'_'.$ret->id_queston.'" min="'.$rep->min.'" max="'.$re->max.'"/>';
		echo '<span>'.$rep->min. '- '.$rep->max.'</span>';
	} else if($rep-type == RANGE_FLOT) {
		echo '<input type="number" step="'.$rep->step.'" name="'.REPONSE.'_'.$ret->id_queston.'" min="'.$rep->min.'" max="'.$re->max.'"/>';
		echo '<span>'.$rep->min. '- '.$rep->max.'</span>';
	}
}


function view_question($q) {
	
	$rep = get_question_reponses($q->id_question);
	
	echo '<table>';
		echo '<thead>';
			echo '<th>';
			echo '<td><span>'.$q->num.'</span><span>'.$s->libele.'</span></td>';
			echo '<th>';
		echo '</thead>';
		echo '<tbody>';
			foreach($rep as $val) {
				echo '<td>';
				if($val->type == LITTERAL) 
				echo '</td>';
				echo '<td>';
					afficher_reponse_par_type($rep);
				echo '</td>';
			}
		echo '</tbody>';
		echo '<tfoot>';
			echo '<button name="question">'.VALIDER.'</button>';
		echo '</tfoot>';
	echo '</table>';
}

function view_sondages() {
	$sond = get_all_sondages();	
}




?>
