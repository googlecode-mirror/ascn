<?php
require_once 'Regles.php';
require_once 'Plateau.php';
require_once 'Pion.php';


class Checkers extends Jeu {
	
	private $regles;
	private $plateau;
	
	
	
	public function process() {
		$this->initRegles();
		$this->initPlateau();
		
		$this->addJs(WWW_JS.'jquery.event.drag-2.0.min.js');
		
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
		
		$data = new stdClass();
		
		$data->regles = $this->regles;
		$data->plateau = $this->plateau;
		
		return $data;
	}
	
	
	public function initRegles() {
		$this->regles=new Regles(partie()->option('regles'));
	}
	
	public function initPlateau() {
		$this->plateau=new Plateau();
	}
	
	
	public function getRegles() {
		return $this->regles;
	}
	
	public function getPlateau() {
		return $this->plateau;
	}
	
	
}




