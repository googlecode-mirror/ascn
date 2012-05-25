<?php



class Coup {
	
	public $plateau;
	
	public $pion;
	public $case_to;
	public $pion_mange;
	public $get_promotion;
	
	
	public function __construct($pion, $case_to, $pion_mange = null, $get_promotion = false) {
		$this->plateau = jeu()->getPlateau();
		
		$this->pion = $pion;
		$this->case_to = $case_to;
		$this->pion_mange = $pion_mange;
		$this->get_promotion = $get_promotion;
	}
	
	
	public function execute() {
		$this->plateau->placerPionSur($this->pion, $this->case_to['x'], $this->case_to['y']);
		if(!is_null($this->pion_mange)) {
			$this->plateau->retirerPion($this->pion_mange);
		}
		if($this->get_promotion) {
			$this->pion->est_promu = true;
		}
	}
	
	public function export() {
		$r->pion = $this->pion;
		$r->case_to = $this->case_to;
		$r->pion_mange = $this->pion_mange;
		$r->get_promotion = $this->get_promotion;
		
		return $r;
	}
	
	
	
}