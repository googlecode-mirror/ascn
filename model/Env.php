<?php

/**
 * 
 * Classe environnement, g�re les singletons d'instances
 * de classe correspondant � l'instance actuelle de
 * l'execution d'un script.
 * 
 * G�re aussi la logique de chargement de l'environnement
 * dans l'ordre de priorit� des d�pendances.
 * 
 * @author Julien
 * @version 0.2
 *
 */
class Env {
	
	/*
	 * Singletons
	 */
	private $singleton_smarty=null;
	private $singleton_page=null;
	
	private $singleton_joueur=null;
	private $singleton_partie=null;
	private $singleton_slot=null;
	
	private $singleton_jeu=null;
	private $singleton_module=null;
	
	
	
	/*
	 * true si deja calcul�, mais quand meme null.
	 */
	private $is_smarty=false;
	private $is_page=false;
	
	private $is_joueur=false;
	private $is_partie=false;
	private $is_slot=false;
	
	private $is_jeu=false;
	private $is_module=false;
	
	
	
	public function __construct() {
		if($appli=getValue('appli', false)) {
			$this->initAppli($appli);
		}
	}
	
	/*
	 * Fonctions d'initialisation des variables.
	 * $this->singleton_ ... = ... ;
	 */
	private function initSmarty() {
		require_once DIR_ROOT.'tools/smarty/libs/Smarty.class.php';

		$this->singleton_smarty = new Smarty();
		
		$this->singleton_smarty->force_compile = true;
		$this->singleton_smarty->debugging = false;
		$this->singleton_smarty->caching = false;
		$this->singleton_smarty->cache_lifetime = 120;
		$this->singleton_smarty->cache_dir=DIR_ROOT.'tools/smarty/cache/';
		$this->singleton_smarty->compile_dir=DIR_ROOT.'tools/smarty/templates_c/';
	}
	
	private function initPage() {
		$this->singleton_page=new Page();
	}
	
	
	/**
	 * 
	 * Requiert $_SESSION['joueur_id']
	 */
	private function initJoueur() {
		if(isset($_SESSION['joueur_id']) && !is_null($_SESSION['joueur_id'])) {
			$this->singleton_joueur=new Joueur(intval($_SESSION['joueur_id']));
		} /* else {
			$this->singleton_joueur=new Invite();
		} */
	}
	
	
	/**
	 * 
	 * Requiert getValue('partie'); ou Slot
	 */
	private function initPartie() {
		if($partie_id=getValue('partie', false)) {
			$partie=$partie_id;
		} else if(slot()) {
			$partie=queryLine('
				select *
				from partie
				where partie_id='.slot()->partie_id.'
			');
		} else return;
		
		$this->singleton_partie=new Partie($partie);
	}
	
	
	
	/**
	 * 
	 * Retourne le slot actuel en fonction du parametre
	 * recu en GET ou POST et verifie si jeu et joueur correspondent.
	 * 
	 * GET|POST['slot'] est OBLIGATOIRE depuis la version 0.2
	 * 
	 * Requiert GET|POST['slot'], et de correspondre
	 * au reste de l'environnement.
	 * 
	 * @return Slot
	 */
	private function initSlot() {
		
		// Check if all environnement variables are defined
		if(!$slot_id=getValue('slot', false)) {
			//trace('Unable to initialize Env::slot : slot is not defined');
			return;
		}
		if(is_null(joueur())) {
			//trace('Unable to initialize Env::slot : not logged in');
			return;
		}
		
		$slot=new Slot($slot_id);
		
		// Check if Slot corresponds to the Env
		if(intval($slot->joueur_id)!=intval(joueur()->getID())) {
			//trace('Unable to initialize Env::slot : slot owner and player logged in does not match');
			return;
		}
		
		
		$this->singleton_slot=$slot;
	}
	
	
	
	/**
	 * 
	 * Requiert arg jeu_name ou rien
	 */
	public function initJeu($jeu_name=null) {
		if(!is_null($jeu_name)) {
			require_once DIR_GAMES.$jeu_name.'/'.$jeu_name.'.php';
			$this->singleton_jeu=new $jeu_name();
		}
	}
	
	
	public function initModule($module_name=null) {
		if(!is_null($module_name)) {
			require_once DIR_MODULES.$module_name.'/'.$module_name.'.php';
			$this->singleton_module=new $module_name();
		}
	}
	
	public function setSlot($slot) {
		$this->slot=$slot;
	}
	
	
	
	
	
	
	
	
	
	/*
	 * Accesseur magique
	 */
	
	/**
	 * 
	 * g�n�re un objet et le garde en cache lors
	 * de l'acces � l'attribut ex : $env->page;
	 * 
	 * @param String $key
	 * @throws Exception si le singleton n'est pas d�fini
	 */
	public function __get($key) {
		$singleton='singleton_'.$key;
		
		if(property_exists($this, $singleton)) {
			
			if(!$this->{'is_'.$key}) {
				$this->{'init'.$key}();
				$this->{'is_'.$key}=true;
			}
			
			return $this->$singleton;
		} else {
			throw new Exception('Environnement singleton "'.$singleton.'" not defined.');
		}
	}
	
	
	public function __set($key, $value) {
		$singleton='singleton_'.$key;
		
		if(property_exists($this, $singleton)) {
			$this->$singleton=$value;
		} else {
			throw new Exception('Environnement singleton "'.$singleton.'" not defined.');
		}
	}
	
	
	
	
	
	
	
	/*
	 * Fonctions appart
	 */
	
	/**
	 * 
	 * Un joueur se co, on initialise pour qu'au prochain
	 * acces, le singleton soit recalcul�.
	 * 
	 * @param Joueur $joueur
	 */
	public function setJoueur($joueur) {
		$this->is_joueur=false;
	}
	public function unsetJoueur() {
		$this->singleton_joueur=null;
		$this->is_joueur=true;
	}
	
	
	
	public function debug() {
		$br="\n";
		foreach(array('smarty', 'page', 'joueur', 'partie', 'slot', 'jeu', 'module') as $s) {
			print 'Singleton '.$s.' :'.$br;
			print_r(array(
				'singleton_'.$s	=> $this->{'singleton_'.$s},
				'is_'.$s		=> $this->{'is_'.$s} ? 'true' : 'false'
			));
		}
	}
	
	
	
	public static function requiert($envClassName, $throwsException=true) {
		if(!($envClassName())) {
			if($throwsException)
				throw new EnvException($envClassName);
			else
				return false;
		} else return true;
	}
	
	
}



class EnvException extends Exception {
	public function __construct($s) {
		parent::__construct(utf8_encode(
			'Le singleton "'.$s.'" doit �tre d�fini dans l\'environnement.'
		));
	}
}







