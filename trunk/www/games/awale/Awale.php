<?php



class Awale extends Jeu {
	
	
	public function process() {
		
	}
	
	
	public function getInitialData() {
		$n=3;
		$r->compartiments=array(
								array($n,$n,$n,$n,$n,$n),
								array($n,$n,$n,$n,$n,$n)
								);
		
		return $r;
	}
	
	
	
}
