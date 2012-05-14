<?php



class OrganizeGame extends Page {


	public function process() {
		$slots=queryTab('
			select * from slot
			natural join joueur
			where partie_id='.partie()->getID().'
			order by slot_position
		');
		
		smarty()->assign('slots', $slots);
		smarty()->assign('options', jeu()->getOptions());
		smarty()->assign('isHost', intval(slot()->joueur_id)==intval(partie()->host));
	}

}