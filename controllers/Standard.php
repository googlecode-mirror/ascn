<?php


class Standard extends Gabarit {
	
	
	public function process() {
		$this->page->addCss(WWW_CSS.'general.css');
		
		smarty()->assign(array(
			'www_img'	=> WWW_IMG,
		));
		
		smarty()->assign('site_name', SITE_NAME);
		
	}
	
	
}