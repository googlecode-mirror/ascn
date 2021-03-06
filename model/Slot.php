<?php




class Slot extends DBItem {
	
	
	public function __construct($arg=null) {
		parent::__construct('slot', $arg);
	}
	
	
	
	
	
	private function checkPartieEnCours() {
		if(intval(partie()->etat) != PARTIE::EN_COURS) {
			throw new Exception('
				Impossible de modifier les scores quand la partie n\'est pas en cours.
				partie.etat = '.partie()->etat
			);
		}
	}
	

	public function addScore($double, $save=false) {
		$this->checkPartieEnCours();
		$this->score=''.(floatval($this->score)+floatval($double));
		$save && $this->save();
	}
	public function subScore($double, $save=false) {
		$this->checkPartieEnCours();
		$this->score=''.(floatval($this->score)-floatval($double));
		$save && $this->save();
	}
	public function mulScore($double, $save=false) {
		$this->checkPartieEnCours();
		$this->score=''.(floatval($this->score)*floatval($double));
		$save && $this->save();
	}
	public function divScore($double, $save=false) {
		$this->checkPartieEnCours();
		$this->score=''.(floatval($this->score)/floatval($double));
		$save && $this->save();
	}
	
	
	
	
}