<?php
require_once 'Regles.php';


class Checkers extends Jeu {
	
	private $regles;
	
	
	public function __construct() {
		parent::__construct();
		
		$this->initRegles();
	}
	
	
	public function process() {
		$this->addJs(WWW_JS.'jquery.event.drag-2.0.min.js');
		smarty()->assign('plateau_inverse', intval(slot()->position)==1);
		
		$demi=$this->taille_plateau/2;
		$nb_pion=$demi*($demi-1);
		
		smarty()->assign($this->getArrayParam());
	}
	
	
	public function getInitialData() {
		
		return $data;
	}
	
	
	public function initRegles() {
		$this->regles=new Regles(partie()->option('regles'));
	}
	
	
}




