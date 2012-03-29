<?php
session_start();


//define('SQL_DEBUG', true);



// database
define('DB_host', 'localhost');
define('DB_port', '3306');
define('DB_name', 'games');
define('DB_user', 'root');
define('DB_pass', '');

// roots
define('DIR_ROOT', 'C:/wamp/www/Eclipse_Workspace/ascn/trunk/');
define('WWW_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/Eclipse_Workspace/ascn/trunk/www/');



// dirnames
define('DIRNAME_MODULES', 'modules');
define('DIRNAME_GAMES', 'games');

// dirs
define('DIR_MODEL', DIR_ROOT.'model/');
define('DIR_CTRL', DIR_ROOT.'controllers/');
define('DIR_WWW', DIR_ROOT.'www/');
define('DIR_TPL', DIR_ROOT.'tpl/');
define('DIR_GAMES', DIR_ROOT.'www/'.DIRNAME_GAMES.'/');
define('DIR_MODULES', DIR_ROOT.'www/'.DIRNAME_MODULES.'/');

define('WWW_CSS', WWW_ROOT.'css/');
define('WWW_JS', WWW_ROOT.'js/');
define('WWW_IMG', WWW_ROOT.'img/');
define('WWW_GAMES', WWW_ROOT.DIRNAME_GAMES.'/');
define('WWW_MODULES', WWW_ROOT.DIRNAME_MODULES.'/');



// chargement modèles
require_once DIR_MODEL.'fx.php';
require_once DIR_MODEL.'DB.php';

// probleme autoload :
require_once DIR_MODEL.'Joueur.php';

// autoload model et controllers
function __autoload($class_name) {
	//print $class_name.' - ';
	if(file_exists(DIR_MODEL.$class_name.'.php')) {
		require_once DIR_MODEL.$class_name.'.php';
	} else if(file_exists(DIR_CTRL.$class_name.'.php')) {
		require_once DIR_CTRL.$class_name.'.php';
	} else {
		throw new Exception('Classe '.$class_name.' non trouvée');
	}
}




// Alias 
$env=new Env();
function env() {
	global $env;
	return $env;
}

function smarty()	{ return env()->smarty;	}
/*
function page()		{ return env()->page;	}

function joueur()	{ return env()->joueur;	}
function partie()	{ return env()->partie;	}
function slot()		{ return env()->slot;	}

function module()	{ return env()->module;	}
function jeu()		{ return env()->jeu;	}
*/


// site prefs
define('SITE_NAME', 'Asynchronous Games');


