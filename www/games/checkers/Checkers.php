<?php
require_once 'Regles.php';
require_once 'Plateau.php';
require_once 'Pion.php';


class Checkers extends Jeu {
	
	private $regles = null;
	private $plateau = null;
	
	
	
	public function process() {
		$this->initRegles();
		$this->initPlateau();
		
		$this->addJs(WWW_JS.'jquery.event.drag.min.js');
		
		$demi = $this->regles->taille_plateau/2;
		$nb_pion = $demi*($demi-1);
		
		smarty()->assign(array(
			'plateau_inverse'	=> intval(slot()->position) == 1,
			'regles'			=> $this->regles,
			'nb_joueur'			=> partie()->getNbJoueur(),
		));
	}
	
	
	public function getInitialData() {
		$this->initRegles();
		$this->initPlateau();
		
		$data->regles = $this->regles;
		$data->plateau = $this->plateau;
		$data->tours = new Tours(partie()->option('premier_joueur'));
		
		return $data;
	}
	
	public function getOptions() {
		return array(
			'premier_joueur'	=> Option::premierJoueur(),
			'regles'			=> new Option('Règles',
				array(
					array(
						'key'		=> 'francaises',
						'value'		=> 'Françaises',
					),
					array(
						'key'		=> 'anglaises',
						'value'		=> 'Anglaises',
						'default'	=> true,
					),
				)
			)
		);
	}

	
	
	public function ajax_move() {
		$this->initRegles();
		$this->initPlateau();
		
		$err = $this->plateau->doMoveThis();
		
		if(count($err)) {
			return self::refus($err);
		}
	}
	
	
	public function initRegles() {
		if(is_null($this->regles)) {
			$this->regles=new Regles(partie()->option('regles'));
		}
	}
	
	public function initPlateau() {
		if(is_null($this->plateau)) {
			$this->plateau=new Plateau();
		}
	}
	
	
	public function getRegles() {
		return $this->regles;
	}
	
	public function getPlateau() {
		return $this->plateau;
	}
	
	
	public static function refus($raisons) {
		$data->refus = true;
		$data->raisons = array();
		foreach($raisons as $raison) {
			$data->raisons[] = utf8_encode($raison);
		}
		return $data;
	}
	
	
}




