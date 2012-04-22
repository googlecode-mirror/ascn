<?php




/**
 * 
 * @author Julien
 * @version 0.1
 * 
 * Classe qui gère les tours par tours dans les jeux
 * tels que Tic Tac Toe, Echecs...
 * 
 * Se référe aux Slot::slot_position.
 * 
 * Vocabulaire :
 * 	tour : incrémenté quand chaque joueur a joué un tour
 * 	coup : correspond au slot_position
 * 
 * 
 * Gère :
 * 	- Jouer quand c'est son tour seulement
 * 	- Compte le nombre de coup
 *  - Gère les timeout des joueurs pour chaque tour
 *
 */
class Tours {
	public static $ALEATOIRE=0;
	
	public $tour;
	public $coup;
	
	/**
	 * 
	 * Rotation des joueurs
	 * @var int $rotation 1 si normal, -1 si inversé
	 */
	public $rotation=1;
	
	
	/**
	 * 
	 * Constructeur
	 * @param int $first_player (default = 1, premier joueur)
	 * 		ALEATOIRE : le premier joueur est aléatoire
	 * 		int : numéro du slot qui commence.
	 */
	public function __construct($first_player=1) {
		$this->tour=1;
		
		if($first_player>0) {
			$this->coup=$first_player;
		} else {
			$this->coup=rand(1, partie()->getNbJoueur());
		}
	}
	
	
	public function next() {
		if($this->rotation==0) {
			throw new Exception('rotation est nulle');
		}
		
		$nbj=partie()->getNbJoueur();
		
		
		$this->coup+=$this->rotation;
		
		
		if($this->rotation>0) {
			while($this->coup > $nbj) {
				$this->coup-=$nbj;
				$this->tour++;
			}
		} else {
			while($this->coup < 1) {
				$this->coup+=$nbj;
				$this->tour++;
			}
		}
	}
	
	
	
	public function getTour() {
		return $this->tour;
	}
	public function getCoup() {
		return $this->coup;
	}
	
	
	public function aMoiDeJouer() {
		return slot()->position == $this->tour;
	}
	public function pasAMoiDeJouer() {
		return slot()->position != $this->tour;
	}
	
	
}








