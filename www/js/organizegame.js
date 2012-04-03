

// Actualizer=function(url, data, callback, timeout, dontstartnow);


$(function () {
	var refresh_organize=setInterval(updateOrganizeRequest, 2000);
	
	$(window).hashchange(function() {
		clearInterval(refresh_organize);
	});

});


function updateOrganizeRequest() {
	var data=Page.getValue();
	Modules.action('partiemgr', 'updateOrganize', data);
}


Modules.ajax_updateOrganize=function(r) {
	
	switch(r.partie.etat) {
	case '1': // PREPARATION
		var liste=$('.liste-joueurs');
		
		liste.html('');
		for(var i=0;i<r.slots.length;i++) {
			liste.append('<li>'+r.slots[i].joueur_pseudo+'</li>');
		}
		
		break;
	
	case '2': // EN_COURS
		Page.hash('games/'+r.jeu.name+'?partie='+r.partie.id+'&slot='+r.slot.id);
		break;
		
	case '3': // TERMINEE
		alert('Partie terminee');
		break;
		
		
	default:
		throw 'Etat de partie non reconnu : '+r.partie.etat;
		
		
	}
};

