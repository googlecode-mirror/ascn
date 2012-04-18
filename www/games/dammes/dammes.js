

var dammes = {
	
	init: function() {
		$('.std-case .cliquable').click(function() {
			var id=$(this).parent().attr('id').split('-');
			dammes.kik_event(id[1], id[2]);
		});
	},
	
	
	kik_event: function(x, y) {
		console.log(x, y);
	}
};




