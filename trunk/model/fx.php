<?php


function addslashesSimpleQuote($s) {
	return str_replace("'", "\'", $s);
}


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




function startswith($hay, $needle) {
	return substr($hay, 0, strlen($needle)) === $needle;
}

function streq($str1, $str2) {
	return strcmp($str1, $str2)==0;
}



function display_js_vars($vars) {
	smarty()->assign(array('jsvars' => $vars));
	smarty()->display(DIR_TPL.'jsvars.tpl');
}

