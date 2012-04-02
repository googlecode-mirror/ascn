<?php

class TicTacToe extends Jeu {
	
	
	private static $grid=null;
	
	
	
	public function process() {
		self::load();
		
		
		$cases=array();
		for($i=0;$i<9;$i++) {
			$class='';
			
			switch(self::$grid[$i]) {
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
	
	
	
	
	
	
	public static function ajax_kik() {
		self::load();
		$x=intval(getValue('x'));
		$y=intval(getValue('y'));
		
		$charat=($y*3)+$x;
		
		$nb_red=0;
		$nb_blue=0;
		for($i=0;$i<9;$i++) {
			switch(self::$grid[$i]) {
				case '1': $nb_red++; break;
				case '2': $nb_blue++;break;
			}
		}
		
		if(self::$grid[$charat]=='0' && (($nb_red-$nb_blue+1)==intval(slot()->position)))
			self::$grid[$charat]=intval(slot()->position);
		
		self::save();
		
		return self::ajax_update();
	}
	
	
	
	public static function ajax_update() {
		self::load();
		
		$res=new AJAXResponse();
		$res->grid=self::$grid;
		return $res;
	}
	
	
	
	
	
	private static function save() {
		$serial='';
		
		foreach(self::$grid as $g) {
			$serial.=$g;
		}
		
		partie()->data=$serial;
		partie()->save();
	}
	private static function load() {
		self::$grid=array();
		
		if(strlen(partie()->data)!=9) {
			partie()->data='000000000';
		}
		
		for($i=0;$i<9;$i++) {
			self::$grid[]=intval(partie()->data{$i});
		}
	}
	
	
}