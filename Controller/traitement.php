<?php

  include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/constante.php');

  // Page de traitement des actions
   
  $msg = 1;
  switch($_REQUEST[ACTION]) {
	  case LOGIN :
	  	if(!isset($_POST[LOGIN]) ||
		   !isset($_POST[PWD])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = connection($_POST[LOGIN], $_POST[PWD]);
		}
	  break;
	  case LOGOUT :
	  	if(isset($_SESSION)) {
			unset($_SESSION);
			session_destroy();
		}	  	
	  break;
	  case PARTICIPATE :
		connection_as_voter();		
	  break;
	  case INSCRIPTION :
	  	if(!isset($_POST[LOGIN]) ||
		   !isset($_POST[PWD]) ||
		   !isset($_POST[PWD_BIS])) {
			   $msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg =  inscription($_POST[LOGIN],$_POST[PWD],  $_POST[PWD_BIS]);	
		}
		break;
	  case ADD_SONDAGE :
	  	if(!isset($_POST[LIBELE])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = add_sondage($_POST[LIBELE]);
		}
	  	break;
	  case ADD_QUESTION :
	  	if(!isset($_POST[LIBELE]) ||
		   !isset($_POST[ID])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = add_question($_POST[ID]
							   ,$_POST[LIBELE]);
		}
	  break;
	  case ADD_REPONSE :
	  	if(!isset($_POST[MIN]) ||
		   !isset($_POST[MAX]) ||
		   !isset($_POST[LIBELE]) ||
		   !isset($_POST[ID])) {
			$msg = ERROR_CORRUPTED_DATA;			   
		} else {
			$min = ($_POST[MIN] == "")? 0 : $_POST[MIN];
			$max = ($_POST[MIN] == "")? 0 : $_POST[MAX];
			
			$msg = add_reponse($_POST[ID], 
				$_POST[LIBELE], $min, $max);
		}
	  break;
	  case DELETE_SONDAGE :
	  	if(!isset($_GET[ID])) {
			$msg = ERROR_CORRUPTED_DATA;			   
		} else {
			$msg = 	delete_sondage($_GET[ID]);		
		}
	  break;
	  case DELETE_QUESTION:
	  	if(!isset($_GET[ID])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = delete_question($_GET[ID]);
		}
	  break;
	  case DELETE_USER:
	  	if(!isset($_GET[ID])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = delete_compte_as_admin($_GET[ID]);
		}
	  break;
	  case DELETE_REPONSE :
	  	if(!isset($_GET[ID])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = delete_reponse($_GET[ID]);
		}
	  break;
	  case MONTER_QUESTION :
	  	if(!isset($_GET[NUM1]) ||
		   !isset($_GET[NUM2])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			if($_GET[NUM2] == 1) {
				$msg = ALREADY_FIRST;
			} else {
				$msg = swap_question($_GET[NUM1], $_GET[NUM2], $_GET[NUM2] - 1);
			}
		}
	  break;
	  case MONTER_REPONSE :
	  	if(!isset($_GET[NUM1])  ||
		   !isset($_GET[NUM2])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			if($_GET[NUM2] == 1) {
				$msg = ALREADY_FIRST;
			} else {
				$msg = swap_reponse($_GET[NUM1], $_GET[NUM2], $_GET[NUM2] -1);
			}
		}
	  break;
	  case VOTER :
	  	if(!isset($_POST[ID]) ||
		   !isset($_POST[REPONSE])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = vote($_POST[ID], $_POST[REPONSE]);
		}
	  break;
	  case OPEN_CLOSE :
	  	if(!isset($_GET[ID])) {
			$msg = ERROR_CORRUPTED_DATA;
		} else {
			$msg = open_close_sondage($_GET[ID]);
		}
	  break;
	  default :
	  	$msg = ERROR_CORRUPTED_DATA;
	  break;
  }
  
  if($msg != 1) {
	  $_SESSION[MESSAGE_ERROR] = $msg;
  } else{
	  $_SESSION[MESSAGE_SUCCESS] = "Action Effectuée avec success";
  }
 
?>