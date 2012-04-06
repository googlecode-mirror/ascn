<?php



abstract class Jeu extends DBItem {
	
	
	
	/**
	 * 
	 * Construit une instance de DBItem à partir
	 * du nom de la classe finalement étendue.
	 */
	public function __construct() {
		parent::__construct('jeu', queryLine('
			select *
			from jeu
			where jeu_name=\''.strtolower(get_class($this)).'\'
		'));
	}

	
	/**
	 * 
	 * Controlleur du template du jeu.
	 * Attention : pas forcément appellée seulement
	 * une seule fois au début de la partie...
	 */
	public abstract function process();
	
	/**
	 * 
	 * Initialiser les données de la partie lorsque
	 * une partie de ce jeu est créée.
	 * 
	 * @return Object|array contenant les données initiales.
	 * 						(avant d'être encodées en JSON)
	 */
	public abstract function getInitialData();
	
	
	/**
	 * 
	 * A appeler quand le jeu est terminé.
	 * @return AJAXResponse TODO
	 */
	public function terminer() {
		partie()->terminer();
		$r=new AJAXResponse();
		$r->partie_terminee=true;
		$r->scores=partie()->getSlots();
		return $r;
	}
	
	
	
	/**
	 * Processus de création, rejoindre et lancement de partie.
	 * 
	 * @throws Exception en cas de non définition de l'environnement.
	 */
	public function run() {
		
		smarty()->assign('jeu', jeu());
		
		if(partie()) {
			smarty()->assign('partie', partie());
			
			smarty()->assign('host', new Joueur(partie()->host));
			
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
					smarty()->assign('isHost', intval($slot->joueur_id)==intval(partie()->host));
					smarty()->display(DIR_TPL.'organizegame.tpl');
					break;
					
				case Partie::EN_COURS:
					if(is_null(slot())) throw new Exception('Erreur : en cours de jeu mais slot non défini');
					smarty()->assign('slot', slot());
					$this->process();
					$this->display();
					break;
				
				case Partie::TERMINEE:
					smarty()->assign('slot', slot());
					$slots=queryTab('
						select * from slot
						natural join joueur
						where partie_id='.partie()->getID().'
						order by slot_score desc
					');
					
					smarty()->assign('slots', $slots);
					smarty()->display(DIR_TPL.'scores.tpl');
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
		$dir=$this->getDir();
		$www=$this->getWWW();
		
		$file=$this->name.'.css';
		if(file_exists($dir.$file)) { ?>
			<link rel="stylesheet" type="text/css" href="<?php print $www.$file; ?>" />
		<?php }
		
		$file=$this->name.'.js';
		if(file_exists($dir.$file)) { ?>
			<script src="<?php print $www.$file; ?>" type="text/javascript"></script>
			<script type="text/javascript">
				$(function () {
					<?php print $this->name;?>.init();
				});
			</script>
		<?php }
		
		if(is_null($tpl))
			smarty()->display($this->getDir().$this->name.'.tpl');
		else
			smarty()->display($this->getDir().$tpl);
	}
	
	
	public function getDir() {
		return DIR_GAMES.$this->name.'/';
	}
	public function getWWW() {
		return WWW_GAMES.$this->name.'/';
	}
	
	
	
}
