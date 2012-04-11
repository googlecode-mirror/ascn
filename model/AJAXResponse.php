<?php




class AJAXResponse {
	
	public $has_error=false;
	public $errors=array();
	
	public function __construct() {
	}
	
	
	public function addError($msg) {
		$this->has_error=true;
		$this->errors[]=utf8_encode($msg);
	}
	
	
	
	
	
	public static function error($s) {
		$r=new AJAXResponse();
		$r->addError($s);
		return $r;
	}
	
	
}