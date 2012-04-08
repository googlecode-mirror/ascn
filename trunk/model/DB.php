<?php


class DB_access extends PDO {
	
	private static $instance=null;
	
	public function __construct() {
		parent::__construct('mysql:host='.DB_host.';port='.DB_port.';dbname='.DB_name, DB_user, DB_pass);
	}
	
	public static function getPDOInstance() {
		if(DB_access::$instance==null) {
			DB_access::$instance=new DB_access();
			
			// UTF8 :
			DB_access::$instance->querySimple("SET NAMES 'UTF8'");
		}
		
		return DB_access::$instance;
	}
	
	
	public function onQuery($q) {
		// DEBUB :
		//print $q."<br />\n";
	}
	
	
	
	public function querySimple($q) {
		$this->onQuery($q);
		$this->exec($q);
	}
	
	public function queryTab($q) {
		$this->onQuery($q);
		$res=$this->query($q);
		return $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function queryLine($q) {
		$this->onQuery($q);
		$res=$this->query($q);
		return $res->fetch(PDO::FETCH_ASSOC);
	}
	
	public function queryValue($q) {
		$this->onQuery($q);
		$res=$this->query($q);
		$l=$res->fetch(PDO::FETCH_NUM);
		return isset($l[0]) ? $l[0] : null;
	}

	
	public function queryView($q) {
		$s="'$q'<br>";
		
		$tab=$this->queryTab($q);
		
		if(count($tab)==0) {
			$s.='(Aucun résultats)';
			return $s;
		}
		
		
		$s.='<table border="1">';
		
		$count=0;
		foreach($tab[0] as $key=>$value) {
			if($count%2==0)
				$s.= '<th>'.$key.'</th>';
			
			$count++;
		}
		
		for($i=0;$i<count($tab);$i++) {
			$s.='<tr>';
			for($j=0;$j<count($tab[$i])/2;$j++) {
				$s.= '<td>'.$tab[$i][$j].'</td>';
			}
			$s.='</tr>';
		}
		
		$s.='</table>';
		
		
		return $s;
	}
	
}




function DB() {
	return DB_access::getPDOInstance();
}


function querySimple($q) {
	DB()->querySimple($q);
}

function queryTab($q) {
	return DB()->queryTab($q);
}

function queryLine($q) {
	return DB()->queryLine($q);
}

function queryValue($q) {
	return DB()->queryValue($q);
}

function queryView($q) {
	return DB()->queryView($q);
}
