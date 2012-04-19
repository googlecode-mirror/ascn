<?php



class Test extends Jeu {
	
	
	public function process() {
		var_dump(partie()->getOptions());
		smarty()->assign('option', partie()->option('nom_option'));
	}
	
	
	public function getInitialData() {
	}
	
	
	
}