

var checkers = {
	
	taille_case: 64,
	
	pions: new Array(),
	
	isFirstUpdate: true,
	
	regles: null,
	
	lastMove: null,
	
	init: function() {
		checkers.update_interval = setInterval(function() {
			Jeux.action('checkers', 'update');
		}, 2500);
		
		$('.draggable')
			.drag(function(ev, dd) {
				$(this).css({
					left: dd.offsetX,
					top: dd.offsetY
				});
			},{ relative: true })
			.bind('draginit', function(event, drag) {
				checkers.caseAtPion($(drag.target)).addClass('mvt-from');
			})
			.bind('drag', function(event, drag) {
				$('.mvt-to').removeClass('mvt-to');
				checkers.caseAtPion($(drag.target)).addClass('mvt-to');
			})
			.bind('dragend', function(event, drag) {
				var case_from	= $('.mvt-from').removeClass('mvt-from');
				var case_to		= $('.mvt-to').removeClass('mvt-to');
				checkers.move(case_from, case_to, $(drag.target));
			});
		
		$(window).hashchange(function() {
			clearInterval(checkers.update_interval);
		});
		
		Jeux.action('checkers', 'update');
	},
	
	update_interval: null,
	
	kik_event: function(x, y) {
		console.log(x, y);
	},
	
	
	move: function(case_from, case_to, pion) {
		console.log('move '+case_from.attr('id')+' move to '+case_to.attr('id'));
		
		if(!case_from.length) {
			throw ('Erreur, case depart undefined');
			return;
		}
		if(!case_to.length) {
			checkers.placerPionSur(pion, case_from);
			return;
		}
		
		
		var deplacement = case_from.attr('id') != case_to.attr('id');
		
		if(deplacement) {
			if(!checkers.lastMove) {
				var _from	= case_from.attr('id').split('-');
				var _to		= case_to.attr('id').split('-');
				
				var data = {
					case_from:	{ x: _from[2],	y: _from[1]	},
					case_to:	{ x: _to[2],	y: _to[1]	}
				};
				
				checkers.lastMove = {
					case_from:	case_from,
					case_to:	case_to,
					pion:		pion
				};
				
				checkers.placerPionSur(pion, case_to);
				Jeux.action('checkers', 'move', data);
			} else {
				checkers.placerPionSur(pion, case_from);
			}
		} else {
			checkers.placerPionSur(pion, case_from);
		}
	},
	
	
	ajax_move: function(r) {
		if(r.refus) {
			alert(r.raison);
			checkers.placerPionSur(checkers.lastMove.pion, checkers.lastMove.case_from);
			checkers.lastMove = null;
		} else {
			checkers.ajax_update(r);
		}
		
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
	
	
	_case: function(x, y) {
		return $('#case-'+y+'-'+x);
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
	
	caseAt: function(x, y) {
		var _x = Math.floor(x/checkers.taille_case);
		var _y = Math.floor(y/checkers.taille_case);
		
		var t = checkers.regles.taille_plateau;
		
		if(!plateau_inverse)
			return checkers._case(_x, _y);
		else
			return checkers._case(t-_x-1, t-_y-1);
	},
	
	caseAtPion: function(dom) {
		var x=dom.position().left+checkers.taille_case/2;
		var y=dom.position().top+checkers.taille_case/2;
		return checkers.caseAt(x, y);
	},
	
	placerPion: function(id, case_x, case_y) {
		var pion = checkers.pions[id];
		var c = checkers._case(case_x, case_y);
		
		pion.animate({
			top:	(c.position().left)	+'px',
			left:	(c.position().top)	+'px'
		});
	},
	
	placerPionSur: function(pion_dom, case_dom) {
		var pion_id = checkers.getPionIdFromDom(pion_dom);
		var coords = checkers.getCaseCoordsFromDom(case_dom);
		
		checkers.placerPion(pion_id, coords.x, coords.y);
	},
	
	getCaseCoordsFromDom: function(dom) {
		var classes = dom.attr('id').split(/\s+/);
		
		for(var i = 0; i < classes.length; i++){
			var classe = classes[i].split('-');
			
			if(classe.length == 3 && classe[0] == 'case') {
				return {
					x: classe[1],
					y: classe[2]
				}
			}
		}
		
		return null;
	},
	
	firstUpdate: function(r) {
	
	
		// init checkers.regles
		checkers.regles = r.partie.data.regles;
		
		// init Array pions && placement pion au chargement de la page
		var counter = new Array();
		for(var i=1;i<=nb_joueur;i++) {
			counter[i] = 0;
		}
		
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
		
		
		// supprime pions en trop dans le dom
		for(var i=1;i<=nb_joueur;i++) {
			for(var j=counter[i];j<r.partie.data.regles.nb_pion;j++) {
				var pion = $('#pion-'+i+'-'+j);
				pion && pion.remove();
			}
		}
	}
	
	
};




