<?php




class Joueur extends DBItem {
	
	
	public function __construct($arg=null) {
		parent::__construct('joueur', $arg);
	}
	
	/**
	 * 
	 * Retourne les slots sur lesquels le joueur
	 * actuelle joue.
	 * Si jeu() n'est pas null, filtre les slots de ce jeu.
	 */
	public function getSlots() {
		if(is_null(jeu())) {
			$slots=queryTab('
				select * from slot
				where joueur_id='.joueur()->getID()
			);
		} else {
			$slots=queryTab('
				select * from slot natural join partie
				where joueur_id='.joueur()->getID().'
				and jeu_id='.jeu()->getID()
			);
		}
		
		
		$ret=array();
		
		foreach($slots as $slot) {
			$ret[]=new Slot($slot);
		}
		
		return $ret;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function setPassword($password_clair) {
		$this->password_hash=self::getHash($password_clair);
	}
	
	
	/**
	 * 
	 * Teste une connexion d'un user et renvoi son instance.
	 * 
	 * @param String $user username
	 * @param String $pass password en clair
	 * @return Joueur|integer 
	 * 		Joueur qui s'est logé avec succes
	 * 		-1 pour mauvais pass
	 * 		-2 pour user inexistant
	 * 
	 */
	public static function testerLogins($user, $pass) {
		$res=queryLine('select * from joueur where joueur_pseudo=\''.$user.'\'');
		
		if($res) {
			$hash=self::getHash($pass);
			if(strcmp($res['joueur_password_hash'], $hash)==0) {
				return new Joueur($res);
			} else {
				return -1;
			}
		} else {
			return -2;
		}
	}
	
	
	public static function connexion($user, $pass) {
		$test=self::testerLogins($user, $pass);
		
		if(is_int($test)) {
			return $test;
		} else {
			$_SESSION['joueur_id']=$test->id;
			env()->setJoueur($test);
			return $test;
		}
	}
	
	public static function deconnexion() {
		unset($_SESSION['joueur_id']);
		env()->unsetJoueur();
		return 0;
	}
	
	
	public static function isLogged() {
		return !is_null(joueur());
	}
	
	
	public static function pseudoExists($pseudo) {
		$r=queryValue('
			select count(*)
			from joueur
			where joueur_pseudo=\''.addslashes($pseudo).'\'
		');
		
		return intval($r)!=0;
	}
	
	
	public static function createUser($pseudo, $password) {
		if(!self::pseudoExists($pseudo)) {
			$j=new Joueur();
				$j->pseudo=$pseudo;
				$j->setPassword($password);
			$j->save();
			
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	public static function getHash($pass) {
		return md5($pass);
	}
	
	
	
	
}