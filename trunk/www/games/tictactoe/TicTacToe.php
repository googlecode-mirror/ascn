<?php

class TicTacToe extends Jeu {
	
	
	
	
	
	public function process() {
		
		$this->loadData();
		
		$cases=array();
		for($i=0;$i<9;$i++) {
			$class='';
			
			switch($this->grille[$i]) {
				case 1: $class='red';	break;
				case 2: $class='blue';	break;
			}
			
			$cases[]=array(
				'x'		=> $i%3,
				'y'		=> (int) ($i/3),
				'p'		=> $i%2==0,
				'class'	=> $class
			);
		}
		
		
		smarty()->assign('cases', $cases);
	}
	
	
	public function getInitialData() {
		
		for($i=0;$i<9;$i++) {
			$r->grille[$i]=0;
		}
		
		$this->grille=$r->grille;
		
		return $r;
	}
	
	
	
	private $grille=null;
	
	
	
	public function ajax_kik() {
		$this->loadData();
		
		
		list($nb_red, $nb_blue)=self::compterSignes($this->grille);
		
		$x=intval(getValue('x'));
		$y=intval(getValue('y'));
		
		$charat=($y*3)+$x;
		
		if($this->grille[$charat]==0 && (($nb_red-$nb_blue+1)==intval(slot()->position))) {
			$this->grille[$charat]=intval(slot()->position);
			$this->saveData();
		}
		
		
		switch($winner=self::checkTicTacToe($this->grille)) {
			case 0:
				return $this->ajax_update();
				
			case 1:case 2:
				$slots=partie()->getSlots();
				$slots[$winner-1]->addScore(1);
				$this->terminer();
				return new AJAXResponse();
				break;
				
			default:
				throw new Exception('grille de Tic Tac Toe impossible');
		}
		
		
	}
	
	
	
	
	public function ajax_update() {
		$this->loadData();
		
		$res=new AJAXResponse();
		$res->grid=$this->grille;
		return $res;
	}
	
	
	private function loadData() {
		if(is_null($this->grille)) {
			$data=json_decode(partie()->data);
			$this->grille=$data->grille;
		}
	}
	
	private function saveData() {
		$data->grille=$this->grille;
		partie()->data=json_encode($data);
		partie()->save();
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param Array $grille a évaluer. Doit être un array de 9 int = 0:vide, 1:joueur1 ou 2:joueur2
	 * @return integer :
	 * 				0 : pas fini
	 * 				1 : J1 win
	 * 				2 : J2 win
	 * 				-1: Erreur : grille impossible
	 */
	public static function checkTicTacToe($grille) {
		
		// Regarde si un joueur à joué plus que l'autre
		list($nb_red, $nb_blue)=self::compterSignes($this->grille);
		$diff=$nb_red-$nb_blue;
		if($diff<-1 || $diff>1) return -1;
		
		
		
		return 0;
	}
	
	
	private static function compterSignes($grille) {
		$nb_red=0;
		$nb_blue=0;
		for($i=0;$i<9;$i++) {
			switch($grille[$i]) {
				case 1: $nb_red++; break;
				case 2: $nb_blue++;break;
			}
		}
		
		return array($nb_red, $nb_blue);
	}
	
	
}