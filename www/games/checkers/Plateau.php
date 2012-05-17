<?php



class Plateau {

	public $cases = null;
	public $taille_plateau = null;

	public function __construct() {
		$partie_data = partie()->getData();
		
		$regles = jeu()->getRegles();
		
		$this->taille_plateau=$regles->taille_plateau;
		
		$this->cases = $this->creerCases($this->taille_plateau);
		
		$this->placerPieces($partie_data);
	}
	
	
	private function placerPieces($partie_data = null) {
	
		if(!isset($partie_data->plateau->cases) || is_null($partie_data->plateau->cases)) {
			$t=$this->taille_plateau;
			$demi=$t/2;
			$nb_pion=$demi*($demi-1);
			
			for($i=0;$i<($demi-1);$i++) {
				for($j=0;$j<$demi;$j++) {
					$x=$i;
					$y=$j*2+$i%2;
					
					$p = new Pion(1);
					$p->placerSur($x, $y);
					$this->cases[$x][$y] = $p;
					
					if(partie()->getNbJoueur() > 1) {
						$p = new Pion(2);
						$p->placerSur($t-$x-1, $t-$y-1);
						$this->cases[$t-$x-1][$t-$y-1] = $p;
					}
				}
			}
		} else {
			for($i=0;$i<$this->taille_plateau;$i++) {
				for($j=0;$j<$this->taille_plateau;$j++) {
					if(!is_null($pion = $partie_data->plateau->cases[$i][$j])) {
						$this->cases[$i][$j] = new Pion($pion);
						
					}
				}
			}
		}
	}
	
	
	public function _case($x, $y) {
		return $this->cases[$x][$y];
	}
	
	
	private function creerCases($taille) {
		$cases = array();
		
		for($i=0; $i < $taille ; $i++) {
			$a = array();
			for($j=0; $j < $taille ; $j++) {
				$a[] = null;
			}
			$cases[] = $a;
		}
		
		return $cases;
	}






}