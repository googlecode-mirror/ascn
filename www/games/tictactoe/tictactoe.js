



var tictactoe = {
	
	init: function () {
		console.log($('.item'));
		$('.item').click(function () {
			var id=$(this).attr('id');
			
			var s=id.split('-');
			
			kik(s[1], s[2]);
			
		});
		
		//tttAction('update');
	},
	
	
	tttAction: function(action, params) {
		ajaxAction('jeu:TicTacToe', action, params);
	},
	
	
	item: function(x, y) {
		return $('#case-'+x+'-'+y);
	},
	
	
	
	kik: function(x, y) {
		tttAction('kik', {x: x, y: y});
	},
	
	
	ajax_tictactoe_kik: function(res) {
		ajax_tictactoe_update(res);
	},
	
	
	ajax_tictactoe_update: function(res) {
		for(var i=0;i<9;i++) {
			var classe='';
			
			switch(res.grid[i]) {
				case 1: classe='red'; break;
				case 2: classe='blue';
			}
			
			item(i%3, Math.floor(i/3))
				.children()
				.attr('class', 'item '+classe);
		}
		
		setTimeout(function() { tttAction('update'); }, 1000);
	}


};
