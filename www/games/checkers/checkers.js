

var checkers = {
	
	taille_case: 64,
	
	pions: new Array(),
	
	isFirstUpdate: true,
	
	param: null,
	
	init: function() {
		checkers.update_interval = setInterval(function() {
			Jeux.action('checkers', 'update');
		}, 2500);
		
		$(window).hashchange(function() {
			clearInterval(checkers.update_interval);
		});
		
		Jeux.action('checkers', 'update');
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
		if(checkers.isFirstUpdate) {
			checkers.firstUpdate(r);
			checkers.isFirstUpdate = false;
		}
		
		// r.partie.data.plateau.cases[][];
		for(var i=0;i<r.partie.data.plateau.taille_plateau;i++) {
			for(var j=0;j<r.partie.data.plateau.taille_plateau;j++) {
				var pion = r.partie.data.plateau.cases[i][j];
				if(pion) {
					
				}
			}
		}
		
	},
	
	
	getPionIdFromDom: function(dom) {
		var classes = dom.attr('class').split(/\s+/);
		
		for(var i = 0; i < classes.length; i++){
			var classe = classes[i].split('-');
			
			if(classe.length == 2 && classe[0] == 'id') {
				return id = parseInt(classe[1]);
			}
		}
		
		return null;
	},
	
	_case: function(x, y) {
		return $('#case-'+x+'-'+y);
	},
	
	placerPion: function(id, case_x, case_y) {
		var pion = checkers.pions[id];
		var c = checkers._case(case_x, case_y);
		
		pion.animate({
			top:	(c.position().top)+'px',
			left:	(c.position().left)+'px'
		});
	},
	
	firstUpdate: function(r) {
		
		
		// init Array pions && placement pion au chargement de la page
		var counter = new Array();
		counter[1] = counter[2] = 0;
		
		for(var i=0;i<r.partie.data.plateau.taille_plateau;i++) {
			for(var j=0;j<r.partie.data.plateau.taille_plateau;j++) {
				var pion = r.partie.data.plateau.cases[i][j];
				if(pion) {
					var dom = $('#pion-'+pion.slot_position+'-'+(counter[pion.slot_position]++));
					
					dom.addClass('id-'+pion.id);
					
					checkers.pions[pion.id] = dom;
					checkers.placerPion(pion.id, pion.coords.x, pion.coords.y); 
				}
			}
		}
	}
	
	
};




