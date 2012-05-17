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
					
					$ajust = (jeu()->getRegles()->cases_utilisees == Color::BLANCHES) ? 0 : 1 ;
					$y=$j*2+($i+$ajust)%2;
					
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
	
	
	public function canMoveThis() {
		$plateau = $this;
		$from = getValue('case_from', null);
		$to = getValue('case_to', null);
		$tours = Tours::createFrom(partie()->getData()->tours);
		$regles = jeu()->getRegles();
		
		$erreurs = self::canMove($plateau, $from, $to, $tours, $regles);
		
		return $erreurs;
	}
	
	public function _pion($x, $y) {
		return $this->cases[$y][$x];
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
	
	
	public function getCouleurCase($x, $y) {
		return (($x + $y)%2 == 0) ? Color::BLANCHE : Color::NOIRE ;
	}
	public function bonneCouleurCase($x, $y) {
		return $this->getCouleurCase($x, $y) == jeu()->getRegles()->cases_utilisees;
	}
	
	public function distance($x0, $y0, $x1, $y1) {
		if(!$this->bonneCouleurCase($x0, $y0) || !self::bonneCouleurCase($x1, $y1)) {
			throw new Exception('Erreur, une case n\'est pas de la bonne couleur selon les regles.');
		}
		
		return max(abs($x1-$x0), abs($y1-$y0));
	}

	
	/*
	 *	@param $cases array cases du plateau
	 *	@param $from Coords de la case from
	 *	@param $to Coords de la case to
	 *	@param $tours Tours
	 *	@param $regles Regles
	 * 
	 *	@return array des raison pour lesquelles le mouvement est impossible.
	 */
	public static function canMove($plateau = null, $from = null, $to = null, $tours = null, $regles = null) {
		
		// definitions
		if(is_null($plateau)) {
			throw new Exception('$plateau non definie');
		}
		if(is_null($from)) {
			throw new Exception('$from non definie');
		}
		if(is_null($to)) {
			throw new Exception('$to non definie');
		}
		if(is_null($tours)) {
			throw new Exception('$tours non definie');
		}
		if(is_null($regles)) {
			throw new Exception('$regles non definie');
		}
		
		
		// Si la case de départ est bien occupée.
		if(is_null($plateau->_pion($from['x'], $from['y']))) {
			throw new Exception('Case de départ vide.');
		}
		
		// Si la case de départ est la même que celle d'arrivée.
		if(($from['x'] == $to['x']) && ($from['y'] == $to['y'])) {
			throw new Exception('Case de départ est d\'arrivée identiques');
		}


		$pion = $plateau->_pion($from['x'], $from['y']);

		
		// Si c'est bien au slot de jouer.
		if($tours->pasAMoiDeJouer()) {
			return array('Ce n\'est pas à vous de jouer.');
		}
		
		// Si le slot joue bien ses pions et pas ceux de l'adversaire.
		if($pion->slot_position != slot()->position) {
			return array('On ne joue pas les pions de l\'adversaire namého !');
		}
		
		// Si il ne déplace pas sur une case déjà occupée.
		if(!is_null($plateau->_pion($to['x'], $to['y']))) {
			return array('Cette case est déjà occupée.');
		}
		
		// Si le pion va bien sur une case de la bonne couleur
		if(!$plateau->bonneCouleurCase($to['x'], $to['y'])) {
			return array('Vous devez vous déplacer sur la même couleur de case.');
		}
		
		
		$distance = $plateau->distance($from['x'], $from['y'], $to['x'], $to['y']);
		
		
		if(!$pion->est_promu) {
		
			// Pour un pion normal (non promu)
			
			if($distance == 1) {
				if(Coords::direction($from['x'], $from['y'], $to['x'], $to['y'])<0) {
					return array('Vous ne pouvez pas reculer.');
				} else {
					// TODO OK, deplacement simple
				}
			}
			
			if($distance == 2) {
				if(Coords::memeDiagonale($from['x'], $from['y'], $to['x'], $to['y'])) {
					$milieu = Coords::milieu($from['x'], $from['y'], $to['x'], $to['y']);
					
					$pion_milieu = $plateau->_pion($milieu->x, $milieu->y);
					
					if(is_null($pion_milieu)) {
						return array('Vous ne pouvez vous déplacer que d\'une case.');
					} else {
						if($pion_milieu->slot_position == slot()->position) {
							return array('Vous ne pouvez pas sauter vos propre pièces.');
						} else {
							if($pion_milieu->est_promu && !$regles->pion_peut_manger_damme) {
								return array('Vous ne pouvez pas prendre une damme dans les règles de cette partie.');
							} else {
								// TODO OK, deplacement avec prise de $pion_milieu
							}
						}
					}
				}
			}
			
			if($distance > 2) {
				return array('Vous ne pouvez vous déplacer que d\'une case.');
			}
			
		}
		
		
		
		return array('ok');
	}
	
	
	
	
	
	




}