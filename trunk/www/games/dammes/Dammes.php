<?php



class Dammes extends Jeu {
	
	
	public function process() {
		$this->addJs(WWW_JS.'draggable.min.js');
		smarty()->assign('plateau_inverse', false);
	}
	
	
	public function getInitialData() {
		$data->tours=new Tours(partie()->option('premier_joueur'));
		$data->partie_data->cases=array();
		
		for($i=0;$i<10;$i++) {
			$line=array();
			for($j=0;$j<10;$j++) {
				$line[]=0;
			}
			$data->partie_data->cases[]=$line;
		}
		
		for($i=0;$i<20;$i++) {
			$data->partie_data->cases[(int)(($i*2)/10)][(int)(($i*2)%10)]=1;
			$data->partie_data->cases[9-(int)(($i*2+1)/10)][9-(int)(($i*2+1)%10)]=2;
		}
		
		return $data;
	}
	
	
	
}