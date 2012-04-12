<?php




class Opt extends DBItem {
	
	private $values_array=null;
	
	public function __construct($arg=null) {
		parent::__construct('opt', $arg);
	}
	
	
	
	
	public function getValues() {
		if(is_null($this->values_array)) {
			$this->values_array=array();
			
			$options=explode('|', $this->values);
			
			foreach($options as $option) {
				$champs=explode(':', $option);
				$this->values_array[$champs[0]]=$champs[count($champs)>1 ? 1 : 0];
			}
		}
		
		
		return $this->values_array;
	}
	
	
}