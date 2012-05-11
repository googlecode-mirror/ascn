<?php



class Pion {
	
	private static $id_count = 0;
	
	public $id;
	public $slot_position = null;
	public $est_promu = false;
	public $coords = null;
	
	
	
	public function __construct($arg) {
		if(is_int($arg)) {
		
			// Créer un nouveau pion à partir du slot_position
			$this->id = Pion::$id_count++;
			$this->slot_position = $arg;
			
		} else {
		
			// Constructeur par copie
			$this->id = $arg->id;
			$this->slot_position = $arg->slot_position;
			$this->est_promu = $arg->est_promu;
			$this->coords = Coords::createFrom($arg->coords);
			
		}
	}
	
	
	public function placerSur($x, $y) {
		if(is_null($this->coords)) {
			$this->coords = new Coords($x, $y);
		} else {
			$this->coords->set($x, $y);
		}
	}
	
	public function getSlot() {
		return partie()->getSlotNum($this->slot_position);
	}
	
	
	
	
	public function getPlateau() {
		return jeu()->getPlateau();
	}




}