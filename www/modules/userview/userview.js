

var userview = {

	init: function() {
		Modules.refresh('userview');
	},

	ajax_connexion: function(response) {
		switch(response.code) {
			case -1: alert('Mot de passe incorrect');break;
			case -2: alert('Ce nom d\'utilisateur n\'existe pas');break;
			case -3: alert('Un champ n\'a pas été rempli');break;
			case 0:
			default: this.init();
		}
	},
	
	
	ajax_deconnexion: function(response) {
		if(parseInt(response.code)==0) {
			this.init();
		}
	},
	
	createuserJsCheck: function() {
		if($('#lightbox input[name=pseudo]').val()==0) {
			alert('Aie... Le champ du pseudo est vide...');
			return false;
		}
		
		if($('#lightbox input[name=password]').val()==0) {
			alert('Aie... Le champ du mot de passe est vide...');
			return false;
		}
		
		if($('#lightbox input[name=password_repeat]').val()==0) {
			alert('Aie... Le champ du mot de passe répété est vide...');
			return false;
		}
		
		if($('#lightbox input[name=password_repeat]').val()!=$('#lightbox input[name=password]').val()) {
			alert('Aie... Les deux mots de passe doivent etre identiques...');
			return false;
		}
		
		
		return true;
	},
	
	
	ajax_signin: function(r) {
		lightbox.show('Cr&eacute;ation d\'un compte', r.html);
		$('#lightbox input[type=submit]').click(function (){
			if(userview.createuserJsCheck()) {
				lightbox.hide();
				return true;
			} else return false;
		});
	},
	
	
	ajax_signin_submit: function(r) {
		if(r.success) {
			lightbox.show('Nouveau compte', '<p>Votre compte a &eacute;t&eacute; cr&eacute;&eacute; avec Succes !<br />Votre nouveau pseudo : <strong>'+r.pseudo+'</strong>.</p>');
		}
	}

	
}




