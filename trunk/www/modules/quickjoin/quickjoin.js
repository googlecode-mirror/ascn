

$(function () {
	
	var quickjoin_interval=setInterval(function() {
		Modules.action('quickjoin', 'update');
	}, 3000);
	
});



var quickjoin = {
	
	init: function() {
		Modules.refresh('quickjoin', function() {
			Modules.action('quickjoin', 'update');
		});
		
	},
	
	ajax_update: function(r) {
		var liste=$('ul#quickjoin-list');
		
		liste.html('');
		for(var i=0;i<r.parties.length;i++) {
			var partie=r.parties[i];
			
			var line='<a href="games/'+partie.jeu.name+'?partie='+partie.partie.id+'" class="ajaxload">';
			line+=partie.partie.title+' (jeu : '+partie.jeu.title+', hote : '+partie.host.pseudo+')';
			line+='</a>';
			
			liste.append('<li>'+line+'</li>');
		};
		
		Page.overrideAjaxButton();
	}
};

