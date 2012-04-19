<?php


function addslashesSimpleQuote($s) {
	return str_replace("'", "\'", $s);
}


// Gestion GET et POST
function getValue($var, $default=null) {
	if(isset($_GET[$var])) {
		return $_GET[$var];
	} else if(isset($_POST[$var])) {
		return $_POST[$var];
	} else {
		return $default;
	}
}

function isValue($var) {
	return isset($_GET[$var]) || isset($_POST[$var]);
}

function getValues() {
	return array_merge($_GET, $_POST);
}


function debugValue() {
	print_r(getValues());
}
// ======================


function appli_dir($appli_type) {
	$a=array(
		'jeu'	=> DIRNAME_GAMES,
		'module'=> DIRNAME_MODULES
	);
	return $a[$appli_type];
}


function julog($s) {
	file_put_contents(DIR_ROOT.'log.txt', $s."\n\n======================\n\n", FILE_APPEND);
}

// verifie si $hay commence par $needle
function startswith($hay, $needle) {
	return substr($hay, 0, strlen($needle)) === $needle;
}

// verifie si deux chaines sont identiques
function streq($str1, $str2) {
	return strcmp($str1, $str2)==0;
}


/*
function display_js_vars($vars) {
	smarty()->assign(array('jsvars' => $vars));
	smarty()->display(DIR_TPL.'jsvars.tpl');
}
*/
