<?php

session_start();

include_once('constante.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Vue/view.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Model/function.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mini projet de sondage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
  <script type="text/javascript" src="./Controller/controller.js?r=12"></script>
</head>
<body>
	
    <?php
	

		if(isset($_REQUEST[ACTION])) {
			include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/Controller/traitement.php');
		}
		
		if(!isset($_SESSION[CONNECTED])) {
		?>

		<div class="container">
          <div class="jumbotron row">
		    <h1>Mini projet web : Sondage</h1> 
    		<p>Bienvenue sur notre application web.</p>
            <p>Réalisé par Talataou Souley Mounira et Nasser Adjibi</p>
	        <?php affichage_form_acceuil(); ?>
            </div>
			</div>
			<?php
		} else {
			echo '<div class="container">';
				affichage($_SESSION[USER_TYPE]);
			echo '</div>';
		}
		

	?>
    
    
</body>
</html>
