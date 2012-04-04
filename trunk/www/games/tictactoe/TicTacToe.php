<?php

class TicTacToe extends Jeu {
	
	
	
	
	
	public function process() {
		
		$this->loadData();
		
		$cases=array();
		for($i=0;$i<9;$i++) {
			$class='';
			
			switch($this->grille[$i]) {
				case 1: $class='red';	break;
				case 2: $class='blue';	break;
			}
			
			$cases[]=array(
				'x'		=> $i%3,
				'y'		=> (int) ($i/3),
				'p'		=> $i%2==0,
				'class'	=> $class
			);
		}
		
		
		smarty()->assign('cases', $cases);
	}
	
	
	public function getInitialData() {
		
		for($i=0;$i<9;$i++) {
			$r->grille[$i]=0;
		}
		
		$this->grille=$r->grille;
		
		return $r;
	}
	
	
	
	private $grille=null;
	
	
	
	public function ajax_kik() {
		$this->loadData();
		
		$x=intval(getValue('x'));
		$y=intval(getValue('y'));
		
		$charat=($y*3)+$x;
		
		$nb_red=0;
		$nb_blue=0;
		for($i=0;$i<9;$i++) {
			switch($this->grille[$i]) {
				case 1: $nb_red++; break;
				case 2: $nb_blue++;break;
			}
		}
		
		if($this->grille[$charat]==0 && (($nb_red-$nb_blue+1)==intval(slot()->position))) {
			$this->grille[$charat]=intval(slot()->position);
			$this->saveData();
		}
		
		return $this->ajax_update();
	}
	
	
	
	public function ajax_update() {
		$this->loadData();
		
		$res=new AJAXResponse();
		$res->grid=$this->grille;
		return $res;
	}
	
	
	private function loadData() {
		if(is_null($this->grille)) {
			$data=json_decode(partie()->data);
			$this->grille=$data->grille;
		}
	}
	
	private function saveData() {
		$data->grille=$this->grille;
		partie()->data=json_encode($data);
		partie()->save();
	}
	
	
	
}