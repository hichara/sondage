 <?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function.php');



function affichage_user($id) {
	?>
		<div id="graphe_container"></div>
    <?php
	affichage_sondages_for_user($id);	
}

function affichage_reponses_of_question_for_user($q_id) {
	$reponses = get_question_reponses($q_id);
	
	echo '<div id="list_reponses_'.$q_id.'" class="panel-collapse collapse">';
	echo '<table>';
	 foreach($reponses as $key => $r) {
		echo '<tr>';
		?>
		 </td>
	     	<td>
				<a href="<?php echo ROOT.'?action='.DELETE_REPONSE.'&'.ID.'='.$r->ID_REPONSE ?>" class="need_confirm">
                	Supprimer
                </a>
                <span>  -  </span>
                <a href="<?php echo ROOT.'?action='.MONTER_REPONSE.'&'.NUM1.'='.$q_id.'&'.NUM2.'='.$r->NUM ?>">
                	Monter</a>
                <span>  -  </span>
			</div>
		 </td>
         <?php
 		 echo '<td>';
          echo '<span>  -  </span>';
		 	if($r->TYPE == TYPE_INTERVAL) {
				echo $r->MIN.'<>'.$r->MAX;
			} else {
				echo $r->LIBELE;
			}        
		echo '</tr>';
	 }
	 echo '</table>';
	 echo '</div>';
}

function afficher_question_for_user($s) {
	$questions = get_sondage_questions($s->ID_SONDAGE);
	
	foreach($questions as $key => $q) {
		$type_question = get_question_type($q->ID_QUESTION);
		$class_stat = 'pull-right badge ';
		if($type_question == TYPE_INTERVAL) {
			$class_stat = $class_stat.'load_pie';
		} else if($type_question == TYPE_CHOIX_MULTIPLE) {
			$class_stat = $class_stat.'load_bar';
		}
	?>
    
    <div class="panel panel-primary col-lg-12">
        	<div class="panel-heading row">
            	<h3 class="panel-title">
					<a data-toggle="collapse"
                    data-target="#list_reponses_<?php echo $q->ID_QUESTION?>">
					<?php echo $q->NUM ?>
                    <?php echo $q->LIBELE ?>
                    </a>
            	    <a href="<?php echo ROOT.'?action='.DELETE_QUESTION.'&'.ID.'='.$q->ID_QUESTION ?>">
                    <button class="pull-right badge need_confirm">Supprimer</button></a>
               		<a href="<?php echo ROOT.'?action='.MONTER_QUESTION.'&'.NUM1.'='.$s->ID_SONDAGE.'&'.NUM2.'='.$q->NUM ?>">
                    <button class="pull-right badge need_confirm">Monter</button></a>
                	<button id="<?php echo $q->ID_QUESTION ?>" class="<?php echo $class_stat ?>">Stats</button>
                    <a data-toggle="collapse" data-target="#list_reponses_<?php echo $q->ID_QUESTION?>">
                    <button class="pull-right badge">Reponses</button></a>
                </h3>

            </div>
            <?php
				affichage_reponses_of_question_for_user($q->ID_QUESTION);
			?>
            <div class="panel-footer row">
            <form role="form" action="<?php echo ROOT ?>" method="POST">
            	<input type="hidden" name="action" value="<?php echo ADD_REPONSE ?>"  />
                <input type="hidden" name="<?php echo ID?>" value="<?php echo $q->ID_QUESTION; ?>" />
                <div class="col-xs-3">
	              <input type="text" name="<?php echo LIBELE?>" class="form-control" placeholder="Reponse"/>
                </div>
                <div class="col-xs-3">
	              <input type="number" name="<?php echo MIN?>" class="form-control text-right" min="0" placeholder="Min"/>
                </div>
                <div class="col-xs-3">
	              <input type="number" name="<?php echo MAX?>" class="form-control text-right" min="0" placeholder="Max"/>
                 </div>
                <div class="col-sm-2">
                 <button class="btn btn-primary">Ajouter</button>
                </div>
              </form>
            </div>
    </div>
    
    <?php
	}
	?>
	 <div class="panel panel-info col-lg-6">
     	<div class="panel-heading row">
        	Ajouter question
        </div>
        <div class="panel-body">
        	<form role="form" action="<?php echo ROOT?>" class="form-inline" method="POST">
            <input type="hidden" name="action" value="<?php echo ADD_QUESTION?>">
	        <input type="text" name="<?php echo LIBELE?>" class="form-control" maxlength="80" size="50" placeholder="Ajouter question(80 Max)"/>
            <input type="hidden" name="<?php echo ID?>" value="<?php echo $s->ID_SONDAGE?>">
            <button type="submit" class="btn btn-primary">Valider</button>
            </form>
        </div>
     </div>
     <?php
}

function affichage_sondages_for_user($id) {
	$sondages = get_user_sondages($id);

	echo '<div class="list-group">';
        echo '<div class="list-group-item list-group-item-info row">';
			echo '<h3>Liste de vos sondages</h3>';
		echo '</div>';
		foreach($sondages as $key => $s) {
			if($s->STATUS == OPEN) {
            echo '<div class="list-group-item row">';
			} else {
            echo '<div class="list-group-item list-group-item-danger row">';
			}
			?>
				<a data-toggle="collapse" 
                href="#sondage_question_<?php echo $key ?>">
					<?php echo ($key + 1).'. '.$s->LIBELE ?>
                </a>
				<a class="need_confirm" href="<?php echo ROOT.'?action='.DELETE_SONDAGE.'&id='.$s->ID_SONDAGE?>">
    	            <button class="pull-right badge">Supprimer</button>
                </a>
				<a class="need_confirm" href="<?php echo ROOT.'?action='.OPEN_CLOSE.'&id='.$s->ID_SONDAGE?>">
 	               <button class="pull-right badge">Ouvrir/Fermer</button>
                </a>
                <button class="badge"
				  data-toggle="collapse" 
        	      data-target="#sondage_question_<?php echo $key ?>">Afficher Question</button>

            </div>
            <div id="sondage_question_<?php echo $key ?>" class="panel-collapse collapse">
             <?php
			 echo '<div class="row">';
            	afficher_question_for_user($s);
			  echo '</div>';
			 ?>
            </div>

            <?php
		}
		?>
		<form role="form" action="<?php echo ROOT?>" method="POST">
        <input type="hidden" name="<?php echo ACTION?>" value="<?php echo ADD_SONDAGE ?>"/>
		<div class="list-group-item row">
			<input type="text" name="<?php echo LIBELE ?>" class="form-control" maxlength="80"  
					placeholder="Ajouter un sondage(80 carectere max)">
		</div>
		<span class="list-group-item row">
			<button type="submit" class="btn btn-primary">Valider</button>
		</span>
	   	</form>
      </div>
    <?php
}




?>