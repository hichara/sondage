
<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Vue/view_voter.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Vue/view_admin.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Vue/view_user.php');


function print_jumbox($type) {
	$voter = false;	
	if($type == VOTER_NUM) {
		$user = "internaute"; 
		$voter = true;
	}
	
	if($type == ADMIN_NUM) {
		$user = "administrateur";
	}
	
	if($type == USER_NUM) {
		$user = "utilisateur";
	}
?>
  <div class="jumbotron row">
    <h1>Mini projet web : Sondage</h1> 
    <p>Vous êtes connect&eacute; en tant qu' <?php echo $user ?></p>
    <a href="http://localhost/sondage/index.php?action=logout" class="btn btn-danger btn-lg">Deconnexion</a>
    <a href="http://localhost/sondage/index.php" class="btn btn-primary btn-lg"> Reload</a>
  </div>
<?php
}

function affichage($type) {
	
	print_jumbox($type);
	
	if(isset($_SESSION[MESSAGE_ERROR])) {
		echo '<div class="panel text-center text-danger panel-danger">';
			echo '<h3 class="bg-danger">'.$_SESSION[MESSAGE_ERROR].'<h3>';
		echo '</div>';
		unset($_SESSION[MESSAGE_ERROR]);
	} 
	
	if(isset($_SESSION[MESSAGE_SUCCESS])) {
		echo '<div class="panel text-center text-success panel-success bg-success">';
			echo '<h3 class="bg-success">'.$_SESSION[MESSAGE_SUCCESS].'<h3>';
		echo '</div>';
		unset($_SESSION[MESSAGE_SUCCESS]);
	} 

	switch($type) {
		case ADMIN_NUM :
			affichage_admin();
		break;
		case USER_NUM :
			affichage_user($_SESSION[ID_USER]);
		break;
		case VOTER_NUM :
			affichage_voter();
		break;
		default:
		break;
	}
}


function affichage_form_acceuil() {
?>

<div class="row">
<div class="col-xs-5 col-xs-offset-4">
	<div class="row" style="background-color:lavender;">
    	<div>
            	<a href="<?php echo ROOT.'?action='.PARTICIPATE; ?>">
                <button type="submit"class="btn btn-md btn-block btn-primary">
                Participer a un sondage
	            </button>
				</a>
		</div>
    </div>
	<div class="row" style="background-color:lavender;">
    	<div>
			<button
            data-toggle="collapse"
            data-target="#form-connexion"
             class="btn btn-md btn-block">Se connecter</button></a>
		</div>
    </div>
	<div id="form-connexion" class="row collapse">
    	<form class="form-horizontal"  action="<?php echo ROOT?>" method="POST">
        	<input type="hidden" name="<?php echo ACTION ?>" value="<?php echo LOGIN?>" >
            <div class="form-group">
            	<label class="control-label col-sm-2">Login</label>
                <div class="col-sm-4">
                <input type="text" name="<?php echo LOGIN ?>" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
            	<label class="control-label col-sm-2">Mot de Passe</label>
                <div class="col-sm-4">
                <input type="password" name="<?php echo PWD?>"class="form-control"/>
                </div>
            </div>
 			<div class="form-group clear-fix"> 
    			<div class="col-sm-offset-2 col-sm-10">
     			<div class="checkbox">
        			<label><input type="checkbox"> Remember me</label>
				 </div>
    			</div>           
            </div>
		   	<div class="form-group"> 
    		<div class="col-sm-offset-2 col-sm-10">
    		 <button name="<?php echo SIGNIN?>" type="submit" class="btn btn-default">OK</button>
		    </div>
 			</div>           
	    </form>
    </div>
    
    <div class="row" style="background-color:lavender;">
    	<div>
			<button
            data-toggle = "collapse"
            data-target = "#form-inscription"
            class="btn btn-block">Inscription</button>
       	</div>
	</div>
    <div>
    <div id="form-inscription" class="row  collapse">
		<form name="signUp" action="<?php echo ROOT ?>" method="POST" role="form" class="form-horizontal">
        	<input type="hidden" name="<?php echo ACTION ?>" value="<?php echo INSCRIPTION?>" >
            <div class="form-group">
            	<label class="control-label col-sm-2">Login</label>
                <div class="col-sm-4">
                <input type="text" name="<?php echo LOGIN ?>" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
            	<label class="control-label col-sm-2">Mot de Passe</label>
                <div class="col-sm-4">
                <input type="password" name="<?php echo PWD?>"class="form-control"/>
                </div>
                <div class="col-sm-4">
                <input type="password" name="<?php echo PWD_BIS?>"class="form-control"/>
                </div>

            </div>
		   	<div class="form-group"> 
    		<div class="col-sm-offset-2 col-sm-10">
    		 <button name="<?php echo SIGNUP?>" type="submit" class="btn btn-default">OK</button>
		    </div>
 			</div>           
	    </form>
	</div>
</div>
</div>
<?php    
    
}




?>