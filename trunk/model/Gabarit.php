<?php




abstract class Gabarit {
	
	public $name;
	public $page;
	
	public function __construct($page) {
		$tpl=explode('.', get_class($this));
		$this->name=$tpl[0];
		
		$this->page=$page;
	}
	
	public abstract function process();
	
	public function display() {
		smarty()->assign('CONTENT', $this->page->fetch());
		smarty()->display(DIR_TPL.$this->name.'.gabarit.tpl');
	}
	
	
}