<?php






class Coords {
	public $x;
	public $y;
	
	
	public function __construct($x=0, $y=0) {
		$this->x=$x;
		$this->y=$y;
	}
	
	
	
	public static function memeDiagonale($x0, $y0, $x1, $y1) {
		return abs($x1-$x0) == abs($y1-$y0);
	}
	public static function milieu($x0, $y0, $x1, $y1) {
		return new Coords(($x0+$x1)/2, ($y0+$y1)/2);
	}
	
	/**
	 * 
	 * @return int nombre de cases dont le pion avance ou recule
	 * 				en fonction de la position du joueur.
	 * @throws EnvException si slot n'est pas défini.
	 */
	public static function direction($x0, $y0, $x1, $y1) {
		Env::requiert('slot');
		
		$y_diff=$y1-$y0;
		return $y_diff*(3-slot()->position*2);
	}
	
	
	
	public function __toString() {
		return 'Coords ('.$this->x.' ; '.$this->y.')'."\n";
	}
	
}




