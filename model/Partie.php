<?php





class Partie extends DBItem {
	const PREPARATION	= 1 ;
	const EN_COURS		= 2 ;
	const TERMINEE		= 3 ;
	
	private $slots=array();
	private $singleton_data=null;
	
	private $options=null;
	
	
	
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
	
	/*
	 * @param $slot_position int numéro du slot à retourner, 1 est le premier.
	 * @return Slot
	 */
	public function getSlotNum($slot_position) {
		$s=$this->getSlots();
		return $s[$slot_position-1];
	}
	
	
	public function getNbJoueur() {
		return count($this->getSlots());
	}
	
	/**
	 * 
	 * Le joueur de l'environnement rejoind la partie /!\ SI PAS DEJA FAIT
	 * @return Slot
	 */
	public function rejoindre() {
		Env::requiert('joueur');
		Env::requiert('partie');
		if($this->etat!=PARTIE::PREPARATION) {
			throw new Exception('Trop tard pour rejoindre la partie. (code etat partie : '.$this->etat.')');
		}
		
		
		$slot=$this->hasJoueur(joueur());
		
		if(!is_null($slot)) {
			return $slot;
		}
		
		
		$s=new Slot();
			$s->partie_id=$this->getID();
			$s->joueur_id=joueur()->getID();
			$s->position=count($this->slots)+1;
		$s->save();
		
		$this->slots[]=$s;
		
		return $s;
	}
	
	
	
	public function quitter() {
		Env::requiert('joueur');
		Env::requiert('partie');
		Env::requiert('slot');
		if($this->etat!=PARTIE::PREPARATION) {
			throw new Exception('Trop tard pour quitter la partie. (code etat partie : '.$this->etat.')');
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
		// env check
		Env::requiert('joueur');
		Env::requiert('jeu');
		Env::requiert('partie');
		
		// host check
		if(intval($this->host)!=joueur()->getID()) {
			throw new Exception("Vous n'êtes pas l'hôte de cette partie...");
		}
		
		// nb player check
		$nb_joueur=count($this->getSlots());
		if($nb_joueur<intval(jeu()->nbjoueur_min)) {
			throw new Exception('Pas assez de joueurs : '.jeu()->nbjoueur_min.' minimum requis.');
		}
		if($nb_joueur>intval(jeu()->nbjoueur_max)) {
			throw new Exception('Trop de joueurs : '.jeu()->nbjoueur_min.' maximum possible.');
		}
		
		// init options
		$data = new stdClass;
		$options = new stdClass;
		
		foreach(getValue('options', array()) as $key=>$value) {
			$options->$key = $value;
		}
		
		$data->partie_option = $options;
		$this->setData($data);
		
		// init data
		$data = jeu()->getInitialData();
		$data->partie_option = $options;
		$this->setData($data ? $data : new stdClass);
		
		// start game
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
	
	
	// Options
	
	/**
	 * 
	 * Récuperer la valeur d'une option de la partie
	 * @param String $key nom de l'option
	 * @return String valeur de cette option telle
	 * 				qu'elle a été définie au début de la partie.
	 */
	public function option($key) {
		return $this->optionKey($key);
	}
	public function optionKey($key) {
		return $this->getData()->partie_option->$key;
	}
	public function optionValue($key) {
		$options = jeu()->getOptions();
		return $options[$key]->title;
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
	public function setData($o, $auto_save = false) {
		$this->singleton_data=$o;
		$this->data=json_encode($o);
		if($auto_save) $this->save();
	}
	
	
	
	
	/**
	 * 
	 * Créer une partie en fonction de l'env.
	 * 
	 * @param String $title de la partie
	 * @return Partie qui vient d'etre crée.
	 */
	public static function create($title) {
		Env::requiert('joueur');
		Env::requiert('jeu');
		
		$p=new Partie();
			$p->jeu_id=jeu()->getID();
			$p->host=joueur()->getID();
			$p->title=$title;
			$p->etat=Partie::PREPARATION;
		$p->save();
		
		return $p;
	}
	
	
	
	
}