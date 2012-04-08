<?php





class Partie extends DBItem {
	const PREPARATION	= 1 ;
	const EN_COURS		= 2 ;
	const TERMINEE		= 3 ;
	
	private $slots=array();
	private $singleton_data=null;
	
	
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
	 * 
	 * @param Joueur $joueur
	 * @return Slot qu'occupe le joueur dans cette partie.
	 */
	public function getSlot($joueur) {
		$data=queryLine('
			select *
			from partie
			natural join slot
			natural join joueur
			where joueur_id='.$joueur->getID().'
			and partie_id='.$this->getID()
		);
		
		return new Slot($data);
	}
	
	
	/**
	 * 
	 * Le joueur de l'environnement rejoind la partie /!\ SI PAS DEJA FAIT
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
		
		
		$slot=$this->hasJoueur(joueur());
		
		if(!is_null($slot)) {
			return $slot;
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
	 * Lancer la partie
	 */
	public function lancer() {
		if(!joueur()) {
			throw new Exception('Joueur n\'est pas def dans l\'env');
		}
		if(!jeu()) {
			throw new Exception('Jeu n\'est pas def dans l\'env');
		}
		
		if($this->etat!=Partie::PREPARATION) {
			throw new Exception('La partie n\'est pas en cours de préparation');
		}
		if(intval($this->host)!=joueur()->getID()) {
			throw new Exception("Vous n'êtes pas l'hôte de cette partie...");
		}
		
		$nb_joueur=count($this->getSlots());
		if($nb_joueur<intval(jeu()->nbjoueur_min)) {
			throw new Exception('Pas assez de joueurs : '.jeu()->nbjoueur_min.' minimum requis.');
		}
		if($nb_joueur>intval(jeu()->nbjoueur_max)) {
			throw new Exception('Trop de joueurs : '.jeu()->nbjoueur_min.' maximum possible.');
		}
		
		
		
		$this->etat=Partie::EN_COURS;
		$this->save();
	}
	
	
	
	
	/**
	 * 
	 * Termine la partie. Les scores des slots
	 * sont ensuite triés pour définir le vainqueur.
	 */
	public function terminer() {
		if($this->etat==Partie::EN_COURS) {
			$this->etat=Partie::TERMINEE;
			$this->save();
		} else {
			throw new Exception('La partie n\'est pas en cours : '.$this->etat);
		}
	}
	
	
	
	
	/**
	 * 
	 * 
	 * @param Joueur $joueur à tester si il est dans la partie or not
	 * @return Slot|null
	 */
	public function hasJoueur($joueur) {
		$data=queryLine('
			select *
			from partie
			natural join slot
			natural join joueur
			where partie_id='.$this->getID().'
			and joueur_id='.$joueur->getID()
		);
		
		return $data ? new Slot($data) : null;
	}
	
	
	public function getData() {
		if(is_null($this->singleton_data)) {
			$this->singleton_data=json_decode($this->data);
		}
		
		return $this->singleton_data;
	}
	public function setData($o) {
		$this->data=$this->singleton_data=json_encode($o);
		$this->save();
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
			$p->setData(jeu()->getInitialData());
		$p->save();
		
		return $p;
	}
	
	
	
	
}