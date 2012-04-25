<?php



class Dammes extends Jeu {
	
	private $taille_plateau;
	private $peut_manger_en_arriere;
	private $damme_deplacement_long;
	
	
	
	
	public function process() {
		$this->setRegles();
		$this->addJs(WWW_JS.'jquery.event.drag-2.0.min.js');
		smarty()->assign('plateau_inverse', intval(slot()->position)==1);
		
		$demi=$this->taille_plateau/2;
		$nb_pion=$demi*($demi-1);
		
		smarty()->assign($this->getArrayParam());
	}
	
	
	public function getInitialData() {
		$this->setRegles();
		$data->tours=new Tours(partie()->option('premier_joueur'));
		$data->partie_data->cases=array();
		$data->param=$this->getArrayParam();
		
		for($i=0;$i<8;$i++) {
			$line=array();
			for($j=0;$j<8;$j++) {
				$line[]=0;
			}
			$data->partie_data->cases[]=$line;
		}
		
		$t=$this->taille_plateau;
		$demi=$t/2;
		$nb_pion=$demi*($demi-1);
		
		for($i=0;$i<($demi-1);$i++) {
			for($j=0;$j<$demi;$j++) {
				$x=$i;
				$y=$j*2+$i%2;
				julog(print_r(array($x,$y),true));
				
				$data->partie_data->cases[$x][$y]=1;
				$data->partie_data->cases[$t-$x-1][$t-$y-1]=2;
			}
		}
		
		
		return $data;
	}
	
	
	
	public function setRegles() {
		// switch(partie()->option('regles')) { ... }
		$this->taille_plateau=8;
		$this->peut_manger_en_arriere=false;
		$this->damme_deplacement_long=false;
	}
	
	
	public function getArrayParam() {
		$demi=$this->taille_plateau/2;
		$nb_pion=$demi*($demi-1);
		return array(
			'taille_plateau'			=> $this->taille_plateau,
			'peut_manger_en_arriere'	=> $this->peut_manger_en_arriere,
			'damme_deplacement_long'	=> $this->damme_deplacement_long,
			'nb_pion'					=> $nb_pion,
			'demi'						=> $demi,
		);
	}
	
	
	
	
	public function _data($value=null) {
		if(is_null($value)) {
			return partie()->getData();
		} else {
			return partie()->setData($value);
		}
	}
	
	public function _case($x, $y, $value=null) {
		if(is_null($value)) {
			return intval($this->_data()->partie_data->cases[$y][$x]);
		} else {
			$o=$this->_data();
			$o->partie_data->cases[$y][$x]=$value;
			$this->_data($o);
			return intval($value);
		}
	}
	
	
	public function ajax_move() {
		//print_r($this->_data());
		$case_from	= getValue('case_from', null);
		$case_to	= getValue('case_to', null);
		
		// Si les cases from et to sont bien définie.
		if(is_null($case_from) || is_null($case_to)) {
			throw new Exception('Une coords n\'est pas définie');
		}
		
		$from_x		= $case_from['x'];
		$from_y		= $case_from['y'];
		$to_x		= $case_to['x'];
		$to_y		= $case_to['y'];
		
		$from		= $this->_case($from_x, $from_y);
		$to			= $this->_case($to_x, $to_y);
		
		$tours=Tours::createFrom($this->_data()->tours);
		
		
		
		// Si la case de départ est bien occupée.
		if($from == 0) {
			throw new Exception('Case de départ vide.');
		}
		
		// Si c'est bien au slot de jouer.
		if($tours->pasAMoiDeJouer()) {
			return AJAXResponse::error('Ce n\'est pas à vous de jouer.');
		}
		
		// Si la case de départ est la même que celle d'arrivée.
		if(($from_x == $to_x) && ($from_y == $to_y)) {
			return $this->ajax_update();
		}
		
		// Si le slot joue bien ses pions et pas ceux de l'adversaire.
		if(($from-1)%2 != intval(slot()->position)-1) {
			return AJAXResponse::error('On ne joue pas les pions de l\'adversaire namého !');
		}
		
		// Si il ne déplace pas sur une case déjà occupée.
		if($to != 0) {
			return AJAXResponse::error('Cette case est déjà occupée.');
		}
		
		
		// Si le déplacement est valide, respect des règles des Dammes.
		if(!self::bonneCase($to_x, $to_y)) {
			return AJAXResponse::error('Vous devez vous déplacer en diagonale. n°1');
		}
		
		
		
		$distance=self::distance($from_x, $from_y, $to_x, $to_y);
		
		if($distance == 1) {
			if(Coords::direction($from_x, $from_y, $to_x, $to_y)<0) {
				return AJAXResponse::error('Vous ne pouvez pas reculer.');
			}
		} else if($distance == 2) {
			if(Coords::memeDiagonale($from_x, $from_y, $to_x, $to_y)) {
				$milieu=Coords::milieu($from_x, $from_y, $to_x, $to_y);
				
				$v=$this->_case($milieu->x, $milieu->y);
				
				// TODO Manger en arriere ou pas !!
				
				if($v==0) {
					// case sautée vide
					return AJAXResponse::error('Déplacement non autorisé n°2.');
				} else if((($v-1)%2) == (2-slot()->position)) {
					// pièce sautée adverse
					$this->_case($milieu->x, $milieu->y, 0);
				} else if((($v-1)%2) == (slot()->position-1)) {
					// pièce sautée alliée
					return AJAXResponse::error('Vous ne pouvez pas sauter vos pièces.');
				}
			} else {
				return AJAXResponse::error(
					'Vous devez vous déplacer en diagonale. n°2'
				);
			}
		} else {
			return AJAXResponse::error(
				'Déplacement non autorisé. ('.self::distance($from_x, $from_y, $to_x, $to_y).')'
			);
		}
		
		
		
		// Si tout est ok :
		$this->_case($to_x, $to_y, $from);
		$this->_case($from_x, $from_y, 0);
		
		
		$tours->next();
		$data=$this->_data();
		$data->tours=$tours;
		$this->_data($data);
		
		partie()->save();
		
		return $this->ajax_update();
	}
	
	
	
	private static function bonneCase($x, $y) {
		return (($x+$y)%2) == 0;
	}
	
	private static function distance($x0, $y0, $x1, $y1) {
		if(!self::bonneCase($x0, $y0) || !self::bonneCase($x1, $y1)) {
			throw new Exception('Erreur, une case n\'est pas en diagonale.');
		}
		
		return max(abs($x1-$x0), abs($y1-$y0));
	}
	
	
	
	
}




