<?php




class Regles {
	
	public $regles;
	
	public $taille_plateau;
	public $peut_manger_en_arriere;
	public $damme_deplacement_long;
	public $nb_pion;
	public $cases_utilisees;
	public $pion_peut_manger_damme;
	public $forcer_prise;
	
	
	public function __construct($regles) {
	
		$this->regles=ucfirst($regles);
		
		switch(strtolower($regles)) {
		
			case 'francaises':
				$this->taille_plateau = 10;
				$this->peut_manger_en_arriere = true;
				$this->damme_deplacement_long = true;
				$this->cases_utilisees = Color::NOIRES;
				$this->pion_peut_manger_damme = true;
				$this->forcer_prise = true;
			break;
			
			case 'anglaises':
				$this->taille_plateau = 8;
				$this->peut_manger_en_arriere = false;
				$this->damme_deplacement_long = false;
				$this->cases_utilisees = Color::NOIRES;
				$this->pion_peut_manger_damme = true;
				$this->forcer_prise = true;
			break;
			
			
			default:
				throw new Exception('Règles non reconnues : '.$regles);
			
		}
		
		
		$this->nb_pion = ($this->taille_plateau/2) * ($this->taille_plateau/2 - 1);
	
	}
	
	
	public static function getRegles() {
		return array(
			'Francaises',
			'Anglaises',
		);
	}


}







