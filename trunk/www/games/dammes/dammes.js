

var dammes = {
		
	taille_case: 64,
	
	init: function() {
		$('.std-case .cliquable').click(function() {
			var id=$(this).parent().attr('id').split('-');
			dammes.kik_event(id[1], id[2]);
		});
		
		$('.draggable').Draggable({
			zIndex: 1000,
			ghosting: false,
			opacity: 0.7
		});
		
		dammes.update_interval=setInterval(function() {
			Jeux.action('dammes', 'update');
		}, 2500);
		
		Jeux.action('dammes', 'update');
	},
	
	update_interval: null,
	
	kik_event: function(x, y) {
		console.log(x, y);
	},
	
	
	ajax_update: function(r) {
		var cases=r.partie.data.partie_data.cases;
		
		var blanc_counter=0;
		var noir_counter=0;
		
		for(var i=0;i<10;i++) {
			for(var j=0;j<10;j++) {
				switch(cases[i][j]) {
					case 1:
						$('.pion-blanc').eq(blanc_counter).css({
							top: dammes.taille_case*i+'px',
							left: dammes.taille_case*j+'px',
						});
						blanc_counter++;
						break;
						
					case 2:
						$('.pion-noir').eq(noir_counter).css({
							top: dammes.taille_case*i+'px',
							left: dammes.taille_case*j+'px',
						});
						noir_counter++;
						break;
				}
			}
		}
	},
	
	
};




