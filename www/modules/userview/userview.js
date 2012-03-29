

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
			default:userview_init();
		}
	},
	
	
	ajax_deconnexion: function(response) {
		if(parseInt(response.code)==0) {
			this.init();
		}
	},
	
	createuserJsCheck: function() {
		return true;
	},
	
	
	ajax_signin: function(r) {
		lightbox_show('Cr&eacute;ation d\'un compte', r.html);
		$('#lightbox input[type=submit]').click(function (){
			if(createuserJsCheck()) {
				lightbox_hide();
				return true;
			} else return false;
		});
	},
	
	
	ajax_signin_submit: function(r) {
		if(r.success) {
			lightbox_show('Nouveau compte', '<p>Votre compte a &eacute;t&eacute; cr&eacute;&eacute; avec Succes !<br />Votre nouveau pseudo : <strong>'+r.pseudo+'</strong>.</p>');
		}
	}

	
}




