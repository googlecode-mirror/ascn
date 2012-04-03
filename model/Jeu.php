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
	
	
	public abstract function process();
	
	
	
	public function run() {
		
		smarty()->assign('jeu', jeu());
		
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
					$slots=queryTab('
						select * from slot
						natural join joueur
						where partie_id='.partie()->getID().'
						order by slot_position
					');
					smarty()->assign('slots', $slots);
					smarty()->assign('host', new Joueur(partie()->host));
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
			smarty()->assign('random', rand(100000, 999999));
			smarty()->display(DIR_TPL.'gameindex.tpl');
		}
		
		
		
	}

	
	
	// Ajax actions :
	public function ajax_creer_partie() {
		// Formulaire de création de partie recu
		$r=new AJAXResponse();
		
		if(!joueur()) {
			$r->addError('Vous n\'êtes pas connécté.');
			return $r;
		}
		
		$jeu_id=addslashes(getValue('jeu_id'));
		$jeu_name=addslashes(getValue('jeu_name'));
		$partie_title=addslashes(getValue('partie_title'));
		
		$partie=Partie::create($partie_title);
		
		$r->partie=$partie;
		$r->jeu=jeu();
		return $r;
	}
	
	
	
	
	public function ajax_lancer_partie() {
		// Formulaire de lancement de partie recu
		$r=new AJAXResponse();
		
		try {
			partie()->lancer();
			
			$r->partie=partie();
			$r->slot=partie()->getSlot(joueur());
			$r->jeu=jeu();
		} catch(Exception $e) {
			$r->addError($e->getMessage());
		}
		
		return $r;
	}
	
	
	
	
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
