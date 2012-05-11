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
		Env::requiert('joueur');
		Env::requiert('partie');
		Env::requiert('slot');
		if(partie()->etat!=PARTIE::PREPARATION) {
			throw new Exception('Trop tard pour quitter la partie. (code etat partie : '.partie()->etat.')');
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
		Env::requiert('joueur');
		Env::requiert('jeu');
		Env::requiert('partie');
		
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
		
		
		$vals=getValues();
		
		foreach($vals as $key=>$value) {
			if(startswith($key, 'option_')) {
				$this->setOption(substr($key, 7), $value);
			}
		}
		
		$data=jeu()->getInitialData();
		$this->setData($data ? $data : new stdClass);
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
	
	
	public function getOptions() {
		if(is_null($this->options)) {
			$this->options=array();
			
			$res=queryTab('
				select *
				from opt
				natural join partie_opt
				where partie_id='.$this->getID()
			);
			
			foreach($res as $data) {
				$opt=new Opt($data);
				$values=$opt->getValues();
				$this->options[$opt->name] = array(
					'key'	=> $data['opt_value'],
					'value'	=> $values[$data['opt_value']]
				);
			}
		}
		
		return $this->options;
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
		$options=$this->getOptions();
		return $options[$key]['value'];
	}
	public function optionKey($key) {
		$options=$this->getOptions();
		return $options[$key]['key'];
	}
	
	
	public function setOption($option_id, $value_id) {
		if($this->etat != Partie::PREPARATION) {
			throw new Exception('Impossible de changer les options de la partie apres son lancement.');
		}
		
		querySimple('
			delete from partie_opt
			where opt_id=\''.addslashes($option_id).'\'
			and opt_value=\''.addslashes($value_id).'\'
			and partie_id='.$this->getID()
		);
		
		querySimple('
			insert into partie_opt (
				partie_id,
				opt_id,
				opt_value
			) values (
				'.$this->getID().',
				\''.$option_id.'\',
				\''.$value_id.'\'
			)
		');
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
		$this->singleton_data=$o;
		$this->data=json_encode($o);
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