<?php



class Dammes extends Jeu {
	
	private $taille_plateau;
	private $peut_manger_en_arriere;
	private $damme_deplacement_long;
	
	
	
	
	public function process() {
		$this->setRegles();
		$this->addJs(WWW_JS.'draggable.min.js');
		smarty()->assign('plateau_inverse', false);
		
		$demi=$this->taille_plateau/2;
		$nb_pion=$demi*($demi-1);
		
		smarty()->assign($this->getArrayParam());
	}
	
	
	public function getInitialData() {
		$this->setRegles();
		$data->tours=new Tours(partie()->option('premier_joueur'));
		$data->partie_data->cases=array();
		$data->param=$this->getArrayParam();
		
		for($i=0;$i<8;$i++) {
			$line=array();
			for($j=0;$j<8;$j++) {
				$line[]=0;
			}
			$data->partie_data->cases[]=$line;
		}
		
		$t=$this->taille_plateau;
		$demi=$t/2;
		$nb_pion=$demi*($demi-1);
		
		for($i=0;$i<($demi-1);$i++) {
			for($j=0;$j<$demi;$j++) {
				$x=$i;
				$y=$j*2+$i%2;
				julog(print_r(array($x,$y),true));
				
				$data->partie_data->cases[$x][$y]=1;
				$data->partie_data->cases[$t-$x-1][$t-$y-1]=2;
			}
		}
		
		
		return $data;
	}
	
	
	
	public function setRegles() {
		// switch(partie()->option('regles')) { ... }
		$this->taille_plateau=8;
		$this->peut_manger_en_arriere=false;
		$this->damme_deplacement_long=false;
	}
	
	
	public function getArrayParam() {
		$demi=$this->taille_plateau/2;
		$nb_pion=$demi*($demi-1);
		return array(
			'taille_plateau'			=> $this->taille_plateau,
			'peut_manger_en_arriere'	=> $this->peut_manger_en_arriere,
			'damme_deplacement_long'	=> $this->damme_deplacement_long,
			'nb_pion'					=> $nb_pion,
			'demi'						=> $demi,
		);
	}
	
}