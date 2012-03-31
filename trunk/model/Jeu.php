<?php



abstract class Jeu {
	
	
	private static $instance=null;
	private $object=null;
	
	
	public function __construct() {
		$res=queryLine('
			select *
			from jeu
			where jeu_name=\''.strtolower(get_class($this)).'\'
		');
		
		$this->object=new DBItem('jeu', $res);
	}

	
	public function __get($name) {
		return $this->object->$name;
	}
	
	public function getID() {
		return $this->object->getID();
	}
	
	
	
	
	
	public function run() {
		
		smarty()->assign('jeu', jeu());
		print_r(getValues());
		print_r(partie());
		
		if(slot()) {
			smarty()->assign('slot', slot());
			smarty()->assign('partie', partie());
			
			$this->process();
			$this->display();
			
		} else if(partie()) {
			smarty()->assign('partie', partie());
			
			switch(partie()->etat) {
			
				case Partie::PREPARATION:
					// en cours de préparation
					$slot=partie()->rejoindre();
					display_js_vars(array('slot_id' => $slot->getID()));
					$slots=queryTab('
						select * from slot natural join joueur
						where partie_id='.partie()->getID()
					);
					smarty()->assign('slots', $slots);
					smarty()->assign('isHost', intval($slot->joueur_id)==intval(partie()->host));
					smarty()->display(DIR_TPL.'organizegame.tpl');
					break;
					
				case Partie::EN_COURS:
					// Erreur : en cours de jeu mais slot non défini
					throw new Exception('Erreur : en cours de jeu mais slot non défini');
					break;
				
				case Partie::TERMINEE:
					// terminée
					break;
					
					
				default:
					throw new Exception('Etat de partie non reconnu : '.partie()->etat);
			
			}
		
		} else {
			// Index & formulaire création partie
			smarty()->display(DIR_TPL.'gameindex.tpl');
		}
		
		
		
	}

	
	
	// Ajax actions :
	public function ajax_creer_partie() {
		// Formulaire de création de partie recu
		$r=new AJAXResponse();
		
		$jeu_id=addslashes(getValue('jeu_id'));
		$jeu_name=addslashes(getValue('jeu_name'));
		$partie_title=addslashes(getValue('partie_title'));
		
		$partie=Partie::create($partie_title);
		
		$r->partie_id=$partie->getID();
		return $r;
	}
	
	
	
	protected abstract function process();
	
	/**
	 * 
	 * Affiche le template avec Smarty.
	 * Inclue les CSS et JS par defaut si ils existent.
	 * @param String $tpl nom du template si autre que le defaut.
	 */
	public function display($tpl=null) {
		if(is_null($tpl))
			smarty()->display($this->getDir().$this->name.'.tpl');
		else
			smarty()->display($this->getDir().$tpl);
	}
	
	
	public function getDir() {
		return DIR_GAMES.$this->name.'/';
	}
	
	
	
}
