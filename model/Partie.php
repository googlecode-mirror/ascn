<?php





class Partie extends DBItem {
	const PREPARATION	= 1 ;
	const EN_COURS		= 2 ;
	const TERMINEE		= 3 ;
	
	private $slots=array();
	
	
	public function __construct($arg=null) {
		parent::__construct('partie', $arg);
		if(!is_null($arg)) {
			$this->updateSlots();
		}
	}
	
	
	
	private function updateSlots() {
		$this->slots=DBItem::getCollection('slot', '
			select * from slot
			where partie_id='.$this->getID().' 
			order by slot_position
		');
	}
	
	public function getSlots() {
		return $this->slots;
	}
	
	
	/**
	 * @return Slot
	 */
	public function rejoindre() {
		if(!joueur()) {
			throw new Exception('Vous devez être connécté pour rejoindre une partie');
		}
		if(!partie()) {
			throw new Exception('Partie n\'est pas definie dans l\'environnement');
		}
		if(partie()->etat!=PARTIE::PREPARATION) {
			throw new Exception('Trop tard pour rejoindre la partie. (code etat partie : '.partie()->etat.')');
		}
		
		
		$s=new Slot();
			$s->partie_id=partie()->getID();
			$s->joueur_id=joueur()->getID();
			$s->position=count($this->slots)+1;
		$s->save();
		
		$this->slots[]=$s;
		
		return $s;
	}
	
	
	
	public function quitter() {
		if(!joueur()) {
			throw new Exception('Vous devez être connécté pour rejoindre une partie');
		}
		if(!partie()) {
			throw new Exception('Partie n\'est pas definie dans l\'environnement');
		}
		if(partie()->etat!=PARTIE::PREPARATION) {
			throw new Exception('Trop tard pour quitter la partie. (code etat partie : '.partie()->etat.')');
		}
		if(!slot()) {
			throw new Exception('Slot doit etre defini en env');
		}
		
		
		foreach($this->slots as $slot) {
			querySimple('
				delete from slot
				where slot_id='.slot()->getID
			);
		}
		
		$this->updateSlots();
	}
	
	
	
	
	
	
	
	/**
	 * 
	 * Créer une partie en fonction de l'env.
	 * 
	 * @param String $title de la partie
	 * @return Partie qui vient d'etre crée.
	 */
	public static function create($title) {
		if(is_null(joueur())) throw new Exception('Aucun joueur identifié, meme pas guest');
		if(is_null(jeu())) throw new Exception('Le jeu n\'est pas defini dans l\'env');
		
		$p=new Partie();
			$p->jeu_id=jeu()->getID();
			$p->host=joueur()->getID();
			$p->title=$title;
			$p->etat=Partie::PREPARATION;
			$p->token=self::genererToken();
		$p->save();
		
		return $p;
	}
	
	
	public static function genererToken() {
		return md5(rand().date('h-i-s:H-i-s'));
	}
	
	
	
}