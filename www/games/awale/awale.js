

var awale = {
		
	action: function(action, data) {
		Jeux.action('awale', action, data);
	},
	
	init: function() {
		awale.action('update');
		
		$('.compartiment')
			.click(function() {
				var cs=$(this).attr('id').split('-');
				
				var line=cs[1];
				var num=cs[2];
				
				awale.kik(line, num);
			});
		
		awale.interval=setInterval(function() {
			awale.action('update');
		}, 2500);
		
		$(window).hashchange(function() {
			clearInterval(awale.interval);
		});
	},

	
	
	setHaricot: function(joueur, num, qte) {
		awale.compartiments[joueur][num]=qte;
		awale.refresh_haricots(joueur, num);
	},
	
	
	displayHaricots: function(joueur, num) {
		var qte=awale.compartiments[joueur][num];
		
		$('#compartiment-'+joueur+'-'+num+' .qte_txt').html(qte);
		
		qte=Math.min(qte, 10);
		
		if(qte>0) {
			$('#compartiment-'+joueur+'-'+num).css({
				backgroundPosition: -70*(qte-1)+'px 0'
			});
		} else {
			$('#compartiment-'+joueur+'-'+num).css({
				backgroundPosition: '0 500px'
			});
		}
	},
	
	
	
	refresh_haricots: function() {
		for(var i=0;i<2;i++) {
			for(var j=0;j<6;j++) {
				awale.displayHaricots(i, j);
			}
		}
	},
	
	
	kik: function(line, num) {
		var data=new Object();
		data['line']=line;
		data['num']=num;
		awale.action('kik', data);
	},
	
	
	ajax_kik: function(r) {
		awale.ajax_update(r);
	},
	
	ajax_update: function(r) {
		if(r.partie_terminee) {
			setTimeout(function() {
				Jeux.afficher_scores(r);
			}, 1500);
			
			awale.compartiments=r.lastdata.data.compartiments;
			awale.refresh_haricots();
			$('#grenier-left .score_txt').html(r.lastdata.slots[0].score);
			$('#grenier-right .score_txt').html(r.lastdata.slots[1].score);
		} else {
			awale.compartiments=r.data.compartiments;
			awale.refresh_haricots();
			$('#grenier-left .score_txt').html(r.slots[0].score);
			$('#grenier-right .score_txt').html(r.slots[1].score);
		}
	}
	
	
	
};


