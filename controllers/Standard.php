<?php


class Standard extends Gabarit {
	
	
	public function preprocess() {
		$this->page->addCss(WWW_CSS.'general.css');
		
		
		smarty()->assign('site_name', SITE_NAME);
		
	}
	
	
}