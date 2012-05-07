

var checkers = {
	
	taille_case: 64,
	
	doUpdate: true,
	
	param: null,
	
	init: function() {
		$('.std-case .cliquable').click(function() {
			var id=$(this).parent().attr('id').split('-');
			checkers.kik_event(id[1], id[2]);
		});
		
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
				checkers.move(case_from, case_to);
			});
		
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
	
	caseAtCoords: function(x, y) {
		if(!plateau_inverse)
			return $('#case-'+y+'-'+x);
		else
			return $('#case-'+(7-y)+'-'+(7-x));
	},
	
	caseAt: function(x, y) {
		var _x=Math.floor(x/checkers.taille_case);
		var _y=Math.floor(y/checkers.taille_case);
		if(!plateau_inverse)
			return $('#case-'+_y+'-'+_x);
		else
			return $('#case-'+(7-_y)+'-'+(7-_x));
	},
	
	
	caseAtPion: function(pion) {
		var x=pion.position().left+checkers.taille_case/2;
		var y=pion.position().top+checkers.taille_case/2;
		return checkers.caseAt(x, y);
	},
	
	pionAtCase: function(x, y) {
		var c=checkers.caseAtCoords(x, y).attr('id').split('-');
		
		var px=parseInt(c[2])*checkers.taille_case+'px';
		var py=parseInt(c[1])*checkers.taille_case+'px';
		
		
		for(var i=0;i<checkers.param.nb_pion;i++) {
			for(var j=1;j<=2;j++) {
				var pion=$('#pion-'+j+'-'+i);
				console.log(pion);
				console.log(pion.css('left')+'=='+px+' && '+pion.css('top')+'=='+py);
				
				if(pion.css('left')==px && pion.css('top')==py) {
					return pion;
				}
			}
		}
	},
	
	ajax_update: function(r) {
		checkers.param=r.partie.data.param;
		
		var cases=r.partie.data.cases;
		
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
		
		
		checkers.doUpdate=true;
	}
	
	
};




