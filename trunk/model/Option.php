<?php



class Option {

	public $title;
	public $values;
	
	
	public function __construct($title, $values) {
		$this->title = $title;
		$this->values = $values;
	}
	
	
	
	public static function premierJoueur($with_random = true) {
		$array = array();
		
		if($with_random) {
			$array[] = array(
				'key'		=> 0,
				'value'		=> 'Aléatoire',
				'default'	=> true,
			);
		}
		
		for($i=0;$i<jeu()->nbjoueur_max;$i++) {
			$array[] = array(
				'key'	=> $i+1,
				'value'	=> 'Joueur '.($i+1),
			);
		}
		
		return new Option('Premier Joueur à jouer', $array);
	}


}