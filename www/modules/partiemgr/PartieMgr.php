<?php




class PartieMgr extends Module {
	
	
	public function ajax_updateOrganize() {
		$r=new AJAXResponse();
		
		
		$res=queryTab('
			select *
			from partie
			natural join slot
			natural join joueur
			natural join jeu
			where partie_id='.partie()->getID().'
			order by slot_position
		');
		
		
		$r->partie=partie();
		
		// revoi tout pour garder infos sur joueur
		$r->slots=$res;
		
		$r->slot=partie()->getSlot(joueur());
		$r->jeu=new DBItem('jeu', $res[0]);
		
		return $r;
	}
	
	
	
}