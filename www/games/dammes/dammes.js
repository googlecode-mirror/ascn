

var dammes = {
	
	taille_case: 64,
	initialized: false,
	
	init: function() {
		$('.std-case .cliquable').click(function() {
			var id=$(this).parent().attr('id').split('-');
			dammes.kik_event(id[1], id[2]);
		});
		
		$('.draggable').drag(function(ev, dd) {
			$(this).css({
				top: dd.offsetY,
				left: dd.offsetX
			});
		},{ relative:true });
	
		dammes.update_interval=setInterval(function() {
			Jeux.action('dammes', 'update');
		}, 2500);
		
		$(window).hashchange(function() {
			clearInterval(dammes.update_interval);
		});
		
		Jeux.action('dammes', 'update');
		
		console.log('init OK. 1');
	},
	
	update_interval: null,
	
	kik_event: function(x, y) {
		console.log(x, y);
	},
	
	
	ajax_update: function(r) {
		if((parseInt(r.slot.position) != parseInt(r.partie.data.tours.coup)) && dammes.initialized)
			return;
		
		var cases=r.partie.data.partie_data.cases;
		
		var blanc_counter=0;
		var noir_counter=0;
		
		for(var i=0;i<r.partie.data.param.taille_plateau;i++) {
			for(var j=0;j<r.partie.data.param.taille_plateau;j++) {
				switch(cases[i][j]) {
					case 1:
						$('.pion-blanc').eq(blanc_counter).css({
							top: $('#case-'+i+'-'+j).position().top+'px',
							left: $('#case-'+i+'-'+j).position().left+'px'
						});
						blanc_counter++;
						break;
						
					case 2:
						$('.pion-noir').eq(noir_counter).css({
							top: $('#case-'+i+'-'+j).position().top+'px',
							left: $('#case-'+i+'-'+j).position().left+'px'
						});
						noir_counter++;
						break;
				}
			}
		}
		
		dammes.initialized=true;
	},
	
	
};




