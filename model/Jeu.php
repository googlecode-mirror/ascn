<?php



abstract class Jeu extends DBItem {
	
	
	private $extra_js=array();
	private $extra_css=array();
	
	
	
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
	 * @return AJAXResponse
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
					Env()->setSlot(partie()->rejoindre());
					$page_organize=new OrganizeGame();
					$page_organize->run();
					break;
					
				case Partie::EN_COURS:
					if(is_null(slot())) throw new Exception('Erreur : en cours de jeu mais slot non défini');
					smarty()->assign('slot', slot());
					$this->process();
					$this->display();
					break;
				
				case Partie::TERMINEE:
					$page_scores=new Scores();
					$page_scores->run();
					break;
					
				default:
					throw new Exception('Etat de partie non reconnu : '.partie()->etat);
			
			}
		
		} else {
			// Index & formulaire création partie
			$page_game_index=new GameIndex();
			$page_game_index->run();
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
	
	
	
	public function ajax_update() {
		$r=new AJAXResponse();
		
		$r->partie=array(
			'id'	=> partie()->id,
			'jeu_id'=> partie()->jeu_id,
			'host'	=> partie()->host,
			'title'	=> partie()->title,
			'etat'	=> partie()->etat,
			'data'	=> partie()->getData(),
		);
		
		$r->slot=slot();
		
		return $r;
	}
	
	
	public function getOptions() {
		$res=queryTab('
			select *
			from opt
			where jeu_id='.$this->getID()
		);
		
		$options=array();
		
		foreach($res as $data) {
			$options[]=new Opt($data);
		}
		
		return $options;
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
		
		foreach($this->extra_css as $css_file) { ?>
			<link rel="stylesheet" type="text/css" href="<?php print $css_file; ?>" />
		<?php }
		
		
		
		foreach($this->extra_js as $js_file) { ?>
			<script src="<?php print $js_file; ?>" type="text/javascript"></script>
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
	
	public function addJs($path) {
		$this->extra_js[]=$path;
	}
	public function addCss($path) {
		$this->extra_css[]=$path;
	}
	
}
