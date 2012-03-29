<?php



class Standard extends Gabarit {
	
	
	public function preprocess() {
		$this->page->addCss(WWW_CSS.'general.css');
		
		$this->page->addJs(WWW_JS.'jquery-1.7.1.min.js');
		$this->page->addJs(WWW_JS.'main.js');
		
		smarty()->assign('site_name', SITE_NAME);
		
	}
	
	
}