<?php
//require_once '../config.php';
//require_once DIR_GAMES.'awale/Awale.php';

class Test {
	
	public function __get($key) {
		print $key;
		return array();
	}
	
	public function __set($key, $value) {
		print_r(array($key => $value));
		return 0;
	}
	
}


$a=new Test;

print_r($a->ju[0]=5);



