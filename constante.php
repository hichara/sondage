<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/sondage/bddInfo.php');

define("LISTE", serialize(array('compte', 'question', 'reponse', 'sondage', 'statistique')));
define("ROOT", "http://localhost/sondage/index.php");
define("ERROR_NO_SUCH_TABLE", "Erreur cette table n'existe pas");
define("ERROR_ARRAY_EXPECTED", "Un liste est attendue");
define("ERROR_INEGAL_SIZE", "Erreur les deux tableux doivent être de même taille");
define("ERROR_ARRAY_REQUIRED", "Erreur des tableaux sont attendus");
define("ERROR_NOT_SAME_VALUE", "Pas la même valeur");
define("ERROR_INTERVAL_TYPE_ALREADY_PROVIDED", "Un intervalle de r&eacute;ponse a d&eacute;ja été fournit");
define("ERROR_EXISTING_USER", "Utilisateur deja existant");
define("ERROR_OPERATION_FORBIDDEN", "Opéraion non autorisée");
define("ERROR_LIMIT_NB_QUESTION", "Nombre de question maximum atteint");
define("ERROR_LIMIT_NB_REPONSE", "Nombre de reponse maximum atteint");
define("ERROR_NOT_YOUR_SONDAGE", "<strong>Ce sondage ne vous appartient pas</strong>");
define("ERROR_NOT_YOUR_QUESTION", "<strong>Cette question ne vous appartient pas</strong>");
define("ERROR_NOT_YOUR_REPONSE", "<strong>Cette reponse ne vous appartient pas</strong>");
define("ERROR_CORRUPTED_DATA", "<strong>Les données du formulaire sont corrompues, par faute de l'utilisateur ou du systeme</strong>");
define("ERROR_STR_OUT_OF_LIMIT", "Limite de caractères max dépassée");
define("ALREADY_FIRST", "Deja premier dans la liste");
define("TYPE_INTERVAL", 0);
define("TYPE_CHOIX_MULTIPLE", 1);
define("REPONSE", "reponse");
define("AJOUTER", 'ajouter');

define("OPEN", 1);
define("CLOSE", 0);

define("TYPE_REPONSE", "type_reponse");
define("LITTERAL", "litteral");
define("INTERVAL", "interval");
define("SONDAGE", "sondage");
define("ID", "id");
define("NUM", "num");
define("NUM1", "num1");
define("NUM2", "num2");
define("MIN", "min");
define("MAX", "max");
define("USER_TYPE", "user_type");
define("CONNECTED", "connected");
define("INSCRIPTION", "inscription");
define("LOGIN", "login");
define("PARTICIPATE", "participer");
define("LOGOUT", "logout");
define("ACTION", "action");
define("QUESTION", "question");
define("PWD", 'mdp');
define("PWD_BIS", 'mdp_bis');
define("VOTER", 'voter');
define("SIGNIN", 'signIN');
define("SIGNUP", 'signUp');
define("ID_USER", "id_user");
define("ASC", "ASC");
define("DESC", "DESC");
define("LIBELE", "libele");

define("ADD_SONDAGE", 'add_sondage');
define("ADD_QUESTION", 'add_question');
define("ADD_REPONSE", "add_reponse");
define("DELETE_QUESTION", "delete_question");
define("DELETE_REPONSE", "delete_reponse");
define("DELETE_SONDAGE", 'delete_sondage');
define("DELETE_USER", "delete_user");
define("MONTER_QUESTION", "monter_question");
define("MONTER_REPONSE", "monter_reponse");
define("OPEN_CLOSE", "open_close");

define("LIMIT_LIBELE", 80);
define("LIMIT_LIBELE_REPONSE", 20);
define("LIMIT_NB_QUESTION", 20);
define("LIMIT_NB_REPONSE", 10);

define("COOKIE_LIFE_SPAN", 24 * 3600);
define("COOKIE_QUESTION", "cookie_question_");

define("_ROOT", 'http://localhost/sondage/');

define("WHO_IS_CONNECTED", "WHO_IS_CONNECTED");
define("MESSAGE_SUCCESS", "MESSAGE_SUCCESS");
define("MESSAGE_ERROR", "MESSAGE_ERROR");
?>

