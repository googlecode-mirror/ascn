<?php

class Jouer extends Page {
	
	public function preprocess() {
		$moi=new Joueur(1);
		$this->moi=$moi->pseudo;
	}
	
}