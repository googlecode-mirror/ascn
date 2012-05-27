<?php
require_once 'Regles.php';
require_once 'Plateau.php';
require_once 'Pion.php';
require_once 'Coup.php';


class Checkers extends Jeu {
	
	private $regles = null;
	private $plateau = null;
	
	
	
	public function process() {
		$this->initRegles();
		$this->initPlateau();
		
		$this->addJs(WWW_JS.'jquery.event.drag.min.js');
		
		$demi = $this->regles->taille_plateau/2;
		$nb_pion = $demi*($demi-1);
		
		smarty()->assign(array(
			'plateau_inverse'	=> intval(slot()->position) == 1,
			'regles'			=> $this->regles,
			'nb_joueur'			=> partie()->getNbJoueur(),
		));
	}
	
	
	public function getInitialData() {
		$this->initRegles();
		$this->initPlateau();
		
		$data->regles = $this->regles;
		$data->plateau = $this->plateau;
		$data->tours = new Tours(partie()->option('premier_joueur'));
		$data->prise_multiple = null;
		
		return $data;
	}
	
	public function getOptions() {
		return array(
			'premier_joueur'	=> Option::premierJoueur(),
			'regles'			=> new Option('Règles',
				array(
					array(
						'key'		=> 'francaises',
						'value'		=> 'Françaises',
					),
					array(
						'key'		=> 'anglaises',
						'value'		=> 'Anglaises',
						'default'	=> true,
					),
				)
			)
		);
	}

	
	
	public function ajax_move() {
		$this->initRegles();
		$this->initPlateau();

		$partie_data = partie()->getData();
		
		$coup = $this->plateau->doMoveThis();
		
		if(!is_null($partie_data->prise_multiple) && $pion = $partie_data->prise_multiple->pion) {
			if(($pion->id != $coup->pion->id) || (!$coup->aMange())) {
				return self::refus(array(
					'Vous devez continuer votre prise multiple.',
				));
			}
		}
		
		if(is_array($coup)) {
			return self::refus($coup);
		}
		
		if(!$coup->aMange()) {
			if(Plateau::slotPeutManger($this->plateau, $coup->pion, $this->regles)) {
				return self::refus(array(
					'Vous devez prendre le pion de l\'adversaire.',
				));
			}
		}
		
		
		$coup->execute();
		
		
		$tours = Tours::createFrom($partie_data->tours);
		if(
			$coup->aMange() &&
			!$coup->get_promotion &&
			Plateau::peutManger($this->plateau, $coup->pion, $this->regles)
		) {
			$partie_data->prise_multiple = array(
				'pion'	=> $coup->pion,
			);
		} else {
			$tours->next();
			$partie_data->prise_multiple = null;
		}
		
		switch($res = $this->plateau->partieFinie()) {
			case 1: // slot 1 gagne
			case 2: // slot 2 gagne
				partie()->getSlotNum($res)->addScore(1, true);
			
			case -1: // partie nulle
				partie()->terminer();
			case 0: // partie en cours
				break;
			
			default:
				throw new Exception('résultat inattendu ('.$res.')');
		}
		
		$partie_data->tours = $tours;
		$partie_data->plateau = $this->plateau;
		partie()->setData($partie_data, true);
		
		$data = $partie_data;
		$data->lastMove = $coup->export();
		
		return $data;
	}
	
	
	
	public function initRegles() {
		if(is_null($this->regles)) {
			$this->regles=new Regles(partie()->option('regles'));
		}
	}
	
	public function initPlateau() {
		if(is_null($this->plateau)) {
			$this->plateau=new Plateau();
		}
	}
	
	
	public function getRegles() {
		return $this->regles;
	}
	
	public function getPlateau() {
		return $this->plateau;
	}
	
	
	public static function refus($raisons) {
		$data->refus = true;
		$data->raisons = array();
		foreach($raisons as $raison) {
			$data->raisons[] = utf8_encode($raison);
		}
		return $data;
	}
	
	
}




