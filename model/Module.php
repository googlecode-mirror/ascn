<?php






abstract class Module {
	
	
	public function display($tpl=null) {
		if(is_null($tpl))
			smarty()->display(strtolower(get_class($this)).'.tpl');
		else
			smarty()->display($tpl);
	}
	
	
	public function process() {
		throw new Exception('PHP fonction '.get_class($this).'::proccess() doit être étendue');
	}
	
	public final function run() {
		$this->process();
	}
	
	
	
	
}