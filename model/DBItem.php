<?php

// dependance : DB.inc
/**
 * 
 * Instance représentant une ligne d'une table d'une base de données.
 * Permet de charger, modifier et renvoyer les résultats.
 * 
 * @author Juju
 * @version 3.0
 *
 */

class DBItem {
	
	/**
	 * 
	 * Nom de la table
	 * @var String
	 */
	protected $table_name;
	
	/**
	 * 
	 * id de l'élément que cette instance représente
	 * @var integer $item_id
	 */
	protected $item_id=null;
	
	/**
	 * 
	 * Noms des champs de la table
	 * @var Array $fields
	 */
	protected $fields=array();
	
	
	/**
	 * 
	 * Création de l'instance
	 * @param String $table nom de la table
	 * @param integer|Array|null $arg
	 * 		Integer : charge la ligne selon l'id
	 * 		Array : Récupere les champs de l'array
	 * 		null : Créer une nouvelle ligne lors du prochain save()
	 */
	public function __construct($table, $arg=null) {
		$this->table_name=$table;
		
		
		if(is_null($arg) || is_array($arg)) {
			$table_structure=queryTab('DESC '.$table);
			
			foreach($table_structure as $row) {
				$this->{$this->getMinName($row['Field'])}=null;
				$this->fields[]=$row['Field'];
			}
			
			if(is_array($arg)) {
				$this->item_id=$arg[$table.'_id'];
				
				foreach($this->fields as $field)
					if(isset($arg[$field]))
						$this->{$this->getMinName($field)}=$arg[$field];
				
			} else {
				$this->item_id=null;
			}
			
		} else {
			$this->item_id=$arg;
			$this->select();
		}
	}
	
	
	
	

	
	/**
	 * 
	 * @param String $fieldname
	 * @return String nom d'attribut réduit comme 'joueur_name' => 'name'
	 */
	private function getMinName($fieldname) {
		if(startswith($fieldname, $this->table_name.'_')) {
			return substr($fieldname, strlen($this->table_name)+1);
		} else {
			return $fieldname;
		}
	}
	
	
	
	
	private function select() {
		$data=queryLine('
			select * from '.$this->table_name.'
			where '.$this->table_name.'_id='.$this->item_id
		);
			
		if(is_null($data) || !$data) {
			throw new Exception('Erreur : '.$this->table_name.'(id='.$this->item_id.') n\'existe pas.');
		}
		
		foreach($data as $key=>$value) {
			$this->{$this->getMinName($key)}=$value;
			$this->fields[]=$key;
		}
	}
	
	
	private function insert() {
		$q='insert into ';
		$q.=$this->table_name;
		
		$new_id=queryValue('
			select '.$this->table_name.'_id
			from '.$this->table_name.'
			order by '.$this->table_name.'_id desc
			limit 1
		');
		
		$new_id=intval($new_id)+1;
		
		$this->item_id=$new_id;
		$this->id=$new_id;
		
		$keys=array();
		$values=array();
		
		foreach($this->fields as $field) {
			$keys[]=$field;
			$values[]="'".addslashes($this->{$this->getMinName($field)})."'";
		}
		
		$keys=implode(', ', $keys);
		$values=implode(', ', $values);
		
		$q.='('.$keys.') values('.$values.')';
		
		querySimple($q);
	}
	
	
	
	private function update() {
		$q='update ';
		$q.=$this->table_name;
		$q.=' set ';
		
		
		$keyvalues=array();
		
		foreach($this->fields as $field) {
			$keyvalues[]=$field."='".addslashes($this->{$this->getMinName($field)})."'";
		}
		
		$q.=implode(', ', $keyvalues);
		
		$q.=' where '.$this->table_name.'_id='.$this->item_id;
		
		querySimple($q);
	}
	
	
	
	
	
	
	public function save() {
		if(is_null($this->item_id)) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	
	
	
	
	public function getID() {
		return $this->item_id;
	}
	
	public function existsInDB() {
		return !is_null($this->item_id);
	}
	
	
	
	public static function getItem($object, $query) {
		$line=queryLine($query);
		
		return new $object($line);
	}
	
	public static function getCollection($object, $query) {
		$tab=queryTab($query);
		
		$col=array();
		
		foreach($tab as $line) {
			$col[]=new $object($line);
		}
		
		return $col;
	}
	
	
}

