<?php

class Jouer extends Page {
	
	public function preprocess() {
		$this->test=getValue('p', 'erreur');
	}
	
}