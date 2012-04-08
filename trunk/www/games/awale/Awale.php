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
	
	
	
	
	public function ajax_kik() {
		$line=intval(getValue('line'));
		$num=intval(getValue('num'));
		
		if($line<0 || $line>2) {
			throw new Exception('Erreur, ligne line='.$line.' n\'existe pas');
		}
		if($num<0 || $num>6) {
			throw new Exception('Erreur, colonne num='.$num.' n\'existe pas');
		}
		
		$r=new AJAXResponse();
		
		if(intval(slot()->position) == $line+1) {
			$data=partie()->getData();
			if($data->compartiment[$line][$num]>0) {
				$r->ok=true;
			} else
				throw new Exception('Il n\'y a pas de haricot sur ce compartiment');
		} else
			throw new Exception('Vous ne pouvez pas jouer les compartiments adverses.');
		
		
		return $r;
	}
	
	
	
	
}
