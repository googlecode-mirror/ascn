



var tictactoe = {
	
	init: function () {
		$('.item').click(function () {
			var id=$(this).attr('id');
			
			var s=id.split('-');
			
			tictactoe.kik(s[1], s[2]);
			
		});
		

		var ttt_interval=setInterval(function() {
			tictactoe.tttAction('update');
		}, 1000);
		
		$(window).hashchange(function () {
			clearInterval(ttt_interval);
		});
	},
	
	
	tttAction: function(action, params) {
		Jeux.action('tictactoe', action, params);
	},
	
	
	item: function(x, y) {
		return $('#case-'+x+'-'+y);
	},
	
	
	
	kik: function(x, y) {
		tictactoe.tttAction('kik', {x: x, y: y});
	},
	
	
	ajax_kik: function(res) {
		tictactoe.ajax_update(res);
	},
	
	
	ajax_update: function(res) {
		if(res.partie_terminee) {
			setTimeout(function() {
				Jeux.afficher_scores(res);
			}, 1500);
		}
		
		for(var i=0;i<9;i++) {
			var classe='';
			
			switch(res.grid[i]) {
				case 1: classe='red'; break;
				case 2: classe='blue';
			}
			
			tictactoe.item(i%3, Math.floor(i/3))
				.children()
				.attr('class', 'item '+classe);
		}
		
	}


};
