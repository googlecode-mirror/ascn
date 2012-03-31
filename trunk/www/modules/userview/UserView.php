<?php


class UserView extends Module {
	
	
	
	
	
	public function process() {
		
		if(is_null(joueur())) {
			$this->display('deco.tpl');
		} else {
			smarty()->assign('joueur', joueur());
			$this->display('co.tpl');
		}
		
	}
	
	

	public function ajax_connexion() {
		$user=getValue('user', false);
		$pass=getValue('pass', false);
		
		$ret=new AJAXResponse();
		
		if($user && $pass) {
			$res=Joueur::connexion($user, $pass);
			if(is_int($res)) {
				$ret->code=$res;
			} else {
				$ret->code=0;
			}
		} else $ret->code=-3;
		
		return $ret;
	}
	
	
	
	
	public function ajax_deconnexion() {
		$res=new AJAXResponse();
		$res->code=Joueur::deconnexion();
		return $res;
	}
	
	
	
	public function ajax_signin() {
		$r=new AJAXResponse();
		$r->html=smarty()->fetch(DIR_MODULES.'userview/createuser.tpl');
		return $r;
	}
	
	
	
	
	public function ajax_signin_submit() {
		$r=new AJAXResponse();
		
		$pseudo=getValue('pseudo');
		$password=getValue('password');
		$password_repeat=getValue('password_repeat');
		
		$r->pseudo=$pseudo;
		$r->success=false;
		
		$ok=true;
		
		if(empty($pseudo) || empty($password) || empty($password_repeat)) {
			$r->addError('Un champ n\'a pas été rempli.').
			$ok=false;
		}
		if(preg_match('/^[a-zA-Z0-9]*$/', $pseudo)) {
			if(strlen($pseudo)<4 || strlen($pseudo)>32) {
				$r->addError('Le pseudo doit avoir entre 4 et 32 caractères.');
				$ok=false;
			}
		} else {
			$r->addError('Le pseudo doit avoir entre 4 et 32 caractères.');
			$r->addError('Le pseudo doit contenir seulement des lettres et des chiffres.');
			$ok=false;
		}
		if(!preg_match('/^[a-zA-Z0-9]{4,32}$/', $pseudo)) {
			$r->addError('Le pseudo doit contenir seulement des lettres et des chiffres, et doit avoir entre 4 et 32 caractères.').
			$ok=false;
		}
		if(!streq($password, $password_repeat)) {
			$r->addError('Le mot de passe répété ne correspond pas au premier mot de passe.').
			$ok=false;
		}
		
		if($ok) {
			if(Joueur::createUser($pseudo, $password)) {
				$r->success=true;
			} else {
				$r->addError('Ce pseudo est déjà utilisé.');
			}
		}
		
		
		return $r;
	}
	
	
}




