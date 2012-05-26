<?php






class Coords {
	public $x;
	public $y;
	
	
	public function __construct($x=0, $y=0) {
		$this->x=$x;
		$this->y=$y;
	}
	
	public function set($x=0, $y=0) {
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
	
	
	/*
	 * Renvoi un Coords depuis l'objet $obj.
	 * @param $obj mixed objet à analyser
	 * @param $x_attribute String nom de l'attribut x, si différent de 'x'
	 * @param $y_attribute String nom de l'attribut y, si différent de 'y'
	 * @return Coords
	 */
	public static function createFrom($obj, $x_attribute='x', $y_attribute='y') {
		return new Coords($obj->$x_attribute, $obj->$y_attribute);
	}
	
	/*
	 * Renvoi un array de coords correspondant a chacune
	 * des coordonnées intermédiaire. Exemple :
	 *
	 * getCoordsIntermediares(5, 5, 8, 2)
	 * returne :
	 * array(
	 *  Coords(5, 5),
	 *  Coords(6, 4),
	 *  Coords(7, 3),
	 *  Coords(8, 2),
	 * )
	 */
	public static function getCoordsIntermediares($x0, $y0, $x1, $y1, $bords = false) {
		
		$dx = 0;
		if($x1 > $x0) $dx = 1;
		if($x1 < $x0) $dx = -1;
		
		$dy = 0;
		if($y1 > $y0) $dy = 1;
		if($y1 < $y0) $dy = -1;
		
		if(($dx == 0) && ($dy == 0)) {
			if($bords) {
				return array(
					new Coords($x0, $y0),
				);
			} else {
				return array();
			}
		}
		
		$i = $x0;
		$j = $y0;
		
		$inter = array();
		$secu = 0;
		
		while(
			($dx == 1 ? ($i <= $x1) : ($i >= $x1)) &&
			($dy == 1 ? ($j <= $y1) : ($j >= $y1))
		) {
			$inter[] = new Coords($i, $j);
			
			$i += $dx;
			$j += $dy;
			
			if($secu++ > 100) throw new Exception('Erreur boucle trop longue');
		}
		
		if(!$bords) {
			array_pop($inter);
			array_shift($inter);
		}
		
		return $inter;
	}
	
	
	
	public function __toString() {
		return 'Coords ('.$this->x.' ; '.$this->y.')'."\n";
	}
	
}




