<?php






abstract class Module {
	
	
	public $name;
	
	
	public function __construct() {
		$this->name=get_class($this);
	}
	
	
	
	
	public function display($tpl=null) {
		if(is_null($tpl))
			smarty()->display($this->name.'.tpl');
		else
			smarty()->display($tpl);
	}
	
	
	public abstract function process();
	
	public final function run() {
		$this->process();
	}
	
	
	
	
}