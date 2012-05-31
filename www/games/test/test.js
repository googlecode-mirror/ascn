

var test = {
	
	init: function() {
		var inter = setInterval(test.interval, 1000);
		
		$(window).hashchange(function() {
			clearInterval(inter);
		});
	},
	
	
	interval: function() {
		Jeux.action('test', 'get_players');
	},
	
	ajax_get_players: function(r) {
		console.log(r);
	}
};




