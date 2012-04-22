
/*
$(function () {
	
});
*/


var ping = {
	
	init: function() {
		Modules.refresh('ping', function() {
		
			ping_interval=setInterval(function() {
				ping.evaluer();
			}, 10000);
			
			
			ping.evaluer();
			
		});
	},
	
	
	ajax_update: function(r) {
		
	},
	
	
	evaluating: false,
	eval_start_time: 0,
	request_time: 0,
	
	evaluer: function() {
		if(!ping.evaluating) {
			ping.evaluating=true;
			
			ping.eval_start_time = new Date().getTime();
			
			$.ajax({
				url: 'modules/ping/ping_echo.html?'+Math.floor(Math.random()*1000000),
				type: 'GET',
				timeout: ping.pallier_1,
				
				success: function(r) { ping.ajax_evaluer(r); },
				error: function(x, t, m) {
					if(t==='timeout') {
						ping.setPing('red');
						$('#ping .ping').attr('title', 'ping : >'+ping.pallier_1+'ms');
					} else {
						ping.setPing('nan');
						console.log('erreur bizar : '+t);
					}
					
					ping.request_time=-1;
					ping.evaluating=false;
				}
			});
		}
		
	},
	
	ajax_evaluer: function(r) {
		ping.request_time = new Date().getTime() - ping.eval_start_time;
		ping.setLevelFromMs(ping.request_time);
		$('#ping .ping').attr('title', 'ping : '+ping.request_time+'ms');
		ping.evaluating=false;
	},
	
	
	pallier_0: 150,
	pallier_1: 450,
	
	setLevelFromMs: function(millis) {
		if(millis < ping.pallier_0) ping.setPing('green');
		else if(millis < ping.pallier_1) ping.setPing('orange');
		else ping.setPing('red');
	},
	
	
	setPing: function(level) {
		switch(level) {
			case 0:
			case 'nan':
				ping.pos(0);
				break;
			
			case 1:
			case 'red':
				ping.pos(1);
				break;
			
			case 2:
			case 'orange':
				ping.pos(2);
				break;
			
			case 3:
			case 'green':
				ping.pos(3);
				break;
			
			default:
				throw "Bah merde, ping bg : "+level+", connais pas";
		}
	},
	
	pos: function(x) {
		$('#ping .ping').css({
			backgroundPosition: (x*(-40))+'px 0'
		});
	}
	
	
};

