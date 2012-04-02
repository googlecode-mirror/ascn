<?php




class PartieMgr extends Module {
	
	
	public function ajax_updateOrganize() {
		$r=new AJAXResponse();
		
		
		$res=queryTab('
			select *
			from partie
			natural join slot
			natural join joueur
			where partie_id='.partie()->getID().'
			order by slot_position
		');
		
		
		$r->partie=partie();
		
		// revoi tout pour garder infos sur joueur
		$r->slots=$res;
		
		return $r;
	}
	
	
	
}