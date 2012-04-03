<?php






class QuickJoin extends Module {
	
	
	
	private function getListe() {
		$quickjoin=queryTab('
			select *
			from partie
			natural join jeu
			natural join joueur
			where joueur_id=partie_host
			and partie_etat='.Partie::PREPARATION
		);
		
		$games=array();
		
		foreach($quickjoin as $data) {
			$games[]=array(
				'partie'	=> new Partie($data),
				'host'		=> new Joueur($data),
				'jeu'		=> new DBItem('jeu', $data)
			);
		}
		
		return $games;
	}
	
	
	
	public function process() {
		smarty()->assign('games', $this->getListe());
		$this->display();
	}
	
	
	
	public function ajax_update() {
		$r=new AJAXResponse();
		
		$r->parties=$this->getListe();
		
		return $r;
	}
	
	
	
	
	
	
	
}