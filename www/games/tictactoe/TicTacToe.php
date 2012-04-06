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
		
		
		$winner=self::checkTicTacToe($this->grille);
		switch($winner['etat']) {
			case 0:
				return $this->ajax_update();
				
			case 1:case 2:
				$slots=partie()->getSlots();
				$slots[$winner['etat']-1]->addScore(1, true);
				$r=$this->terminer();
				$r->highlight=$winner['highlight'];
				$r->grid=$this->grille;
				return $r;
				
			default:
				throw new Exception('grille de Tic Tac Toe impossible : '.$winner);
		}
		
		
	}
	
	
	
	
	public function ajax_update() {
		$this->loadData();
		
		$res=new AJAXResponse();
		$res->grid=$this->grille;
		$res->partie_terminee=partie()->etat==PARTIE::TERMINEE;
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
	 * Algo d'évaluation d'une grille de tic tac toe.
	 * @param Array $grille a évaluer. Doit être un array de 9 int = 0:vide, 1:joueur1 ou 2:joueur2
	 * @return Array(
	 * 					'etat' =>
	 * 						0 : pas fini
	 * 						1 : J1 win
	 * 						2 : J2 win
	 * 						-1: Erreur : grille impossible
	 * 					'highlight'
	 * 						=> Array
	 * 
	 */
	public static function checkTicTacToe($grille) {
		
		// Regarde si un joueur à joué plus que l'autre
		list($nb_red, $nb_blue)=self::compterSignes($grille);
		$diff=$nb_red-$nb_blue;
		if($diff<-1 || $diff>1) return array('etat' => -1);
		
		// si moins de 5 signes, pas de victoire possible.
		if(($nb_red+$nb_blue)<5) return array('etat' => 0);
		
		// cherche alignements
		$ret=array();
		$ret['highlight']=array(0,0,0, 0,0,0, 0,0,0);
		
		$g=$grille;
		
		$coefs=array(
			array(3,0),
			array(3,1),
			array(3,2),
			array(1,0),
			array(1,3),
			array(1,6),
			array(4,0),
			array(2,2)
		);
		
		foreach($coefs as $coef) {
			list($a, $b)=$coef;
			
			for($x=0;$x<3;$x++) {
				${'c'.$x}=$g[$a*$x+$b];
			}
			
			if($c0==$c1 && $c1==$c2) {
				for($x=0;$x<3;$x++) {
					$ret['etat']=$c0;
					$ret['highlight'][$a*$x+$b]=1;
				}
				
				return $ret;
			}
		}
		
		
		return array('etat' => 0);
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