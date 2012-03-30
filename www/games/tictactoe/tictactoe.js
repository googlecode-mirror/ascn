
$(function () {
	console.log($('.item'));
	$('.item').click(function () {
		var id=$(this).attr('id');
		
		var s=id.split('-');
		
		kik(s[1], s[2]);
		
	});
	
	//tttAction('update');
});


function tttAction(action, params) {
	ajaxAction('jeu:TicTacToe', action, params);
}


function item(x, y) {
	return $('#case-'+x+'-'+y);
}



function kik(x, y) {
	tttAction('kik', {x: x, y: y});
}


function ajax_tictactoe_kik(res) {
	ajax_tictactoe_update(res);
}


function ajax_tictactoe_update(res) {
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

