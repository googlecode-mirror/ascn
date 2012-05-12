

var checkers = {
	
	taille_case: 64,
	
	doUpdate: true,
	
	param: null,
	
	init: function() {
		checkers.update_interval = setInterval(function() {
			checkers.doUpdate && Jeux.action('checkers', 'update');
		}, 2500);
		
		$(window).hashchange(function() {
			clearInterval(checkers.update_interval);
		});
		
		Jeux.action('checkers', 'update');
		
		console.log('init OK. 1');
	},
	
	update_interval: null,
	
	kik_event: function(x, y) {
		console.log(x, y);
	},
	
	
	move: function(case_from, case_to) {
		console.log('move '+case_from.attr('id')+' move to '+case_to.attr('id'));
		
		var _from	= case_from.attr('id').split('-');
		var _to		= case_to.attr('id').split('-');
		
		var data = {
			case_from:	{ x: _from[2],	y: _from[1]	},
			case_to:	{ x: _to[2],	y: _to[1]	}
		};
		
		Jeux.action('checkers', 'move', data);
	},
	
	
	ajax_move: function(r) {
		checkers.ajax_update(r);
	},
	
	
	ajax_update: function(r) {
		// r.partie.data.plateau.cases[][];
		for(var i=0;i<r.partie.data.plateau.taille_plateau;i++) {
			for(var j=0;j<r.partie.data.plateau.taille_plateau;j++) {
				var pion = r.partie.data.plateau.cases[i][j];
				console.log(pion);
				if(pion) {
					
				}
			}
		}
		
		
		checkers.doUpdate=true;
	},
	
	
	placerPion: function(id, x, y) {
	
	}
	
	
};




