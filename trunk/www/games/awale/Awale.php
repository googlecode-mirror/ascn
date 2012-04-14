<?php



class Awale extends Jeu {
	
	
	
	private $data=null;
	
	
	public function process() {
		smarty()->assign(array(
			'slot_position' => slot()->position
		));
	}
	
	
	public function getInitialData() {
		$n=partie()->option('nb_haricot_initial');
		$r->compartiments=array(
								array($n,$n,$n,$n,$n,$n),
								array($n,$n,$n,$n,$n,$n)
								);
		
		$first=intval(partie()->option('premier_joueur'));
		$r->tour= $first == 0 ? rand(1, 2) : $first;
		
		return $r;
	}
	
	
	public function loadData() {
		$this->data=json_decode(partie()->data);
	}
	public function saveData() {
		partie()->data=json_encode($this->data);
		partie()->save();
	}
	
	
	
	public function ajax_kik() {
		$line=intval(getValue('line'));
		$num=intval(getValue('num'));
		
		if($line<0 || $line>2) throw new Exception('Erreur, ligne line='.$line.' n\'existe pas');
		if($num<0 || $num>6) throw new Exception('Erreur, colonne num='.$num.' n\'existe pas');
		
		
		$this->loadData();
		
		if(slot()->position != $this->data->tour) return AJAXResponse::error('Ce n\'est pas à vous de jouer');
		
		
		if(intval(slot()->position) == $line+1) {
			if($this->data->compartiments[$line][$num]>0) {
				
				$this->redistribuer($line, $num);
				$this->data->tour=3-$this->data->tour;
				$this->saveData();
				
			} else return AJAXResponse::error('Il n\'y a pas de haricot sur ce compartiment');
		} else return AJAXResponse::error('Vous ne pouvez pas jouer les compartiments adverses.');
		
		
		
		$tour=$this->data->tour-1;
		$vide=true;
		for($i=0;$i<6;$i++) {
			if($this->data->compartiments[$tour][$i]>0) {
				$vide=false;
				break;
			}
		}
		
		if($vide) {
			$ramasse=0;
			for($i=0;$i<6;$i++) {
				$ramasse += $this->data->compartiments[1-$tour][$i];
				$this->data->compartiments[1-$tour][$i]=0;
			}
			
			slot()->addScore($ramasse, true);
			$r=$this->terminer();
			$r->lastdata=$this->ajax_update();
			return $r;
		}
		
		
		$slots=partie()->getSlots();
		$moyenne = intval(partie()->option('nb_haricot_initial'))*6;
		if(intval($slots[0]->score)>$moyenne || intval($slots[1]->score)>$moyenne) {
			$r=$this->terminer();
			$r->lastdata=$this->ajax_update();
			return $r;
		}
		
		return $this->ajax_update();
	}
	
	
	
	
	public function ajax_update() {
		$r=new AJAXResponse();
		
		
		$this->loadData();
		$r->data=$this->data;
		$r->slot_position=slot()->position;
		$r->slots=partie()->getSlots();
		$r->partie_terminee=partie()->etat==PARTIE::TERMINEE;
		
		return $r;
	}
	
	
	
	
	private function redistribuer($line, $num) {
		
		$hand=$this->data->compartiments[$line][$num];
		$this->data->compartiments[$line][$num]=0;
		
		$i=0;
		$c=null;
		while($hand>0) {
			$i++;
			$c=self::relative($line, $num, $i);
			$this->data->compartiments[$c[0]][$c[1]]++;
			$hand--;
		}
		
		$this->manger($c[0], $c[1]);
		
	}
	
	private function manger($line, $num) {
		$i=0;
		$continue=false;
		$mange=0;
		
		do {
			$c=self::relative($line, $num, $i);
			if(($c[0]+1) == slot()->position) break;
			
			$nb=$this->data->compartiments[$c[0]][$c[1]];
			
			if($nb==2 || $nb==3) {
				$mange+=$this->data->compartiments[$c[0]][$c[1]];
				$this->data->compartiments[$c[0]][$c[1]]=0;
				$continue=true;
			} else $continue=false;
			
			$i--;
		} while($continue);
		
		slot()->addScore($mange, true);
		$this->saveData();
	}
	
	
	private static $rel=array(
		array(0,5),
		array(0,4),
		array(0,3),
		array(0,2),
		array(0,1),
		array(0,0), //5
		array(1,0), //6
		array(1,1), //7
		array(1,2), //8
		array(1,3), //9
		array(1,4), //10
		array(1,5)  //11
	);
	
	private static function relative($line, $num, $n) {
		$i= $line==0 ? 5-$num : $num+6 ;
		
		$j= ($i+$n)%12;
		if($j<0) $j+=12;
		
		return self::$rel[$j];
	}
	
	
}
