

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
		
		$('.log-icon').click(function() {
			if(quickjoin.open) quickjoin.form_close();
			else quickjoin.form_open();
			
			quickjoin.open = !quickjoin.open;
		});
		
		
	},
	
	open: false,
	
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
	},
	
	
	form_open: function() {
		$('.log-content')
			.animate({
				width: '170px'
			});
			
		$('.log-center')
			.animate({
				opacity: '0'
			});
			
	},
	
	form_close: function() {
		$('.log-content')
			.animate({
				width: '0px'
			});
			
		$('.log-center')
			.animate({
				opacity: '1'
			});
	}
};

