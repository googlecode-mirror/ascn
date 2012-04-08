

var awale = {
	
	init: function() {
		awale.compartiments=[
		               [0,0,0,0,0,0],
		               [0,0,0,0,0,0]
		               		];
		
		awale.refresh_haricots();
		
		$('.compartiment')
			.click(function() {
				var cs=$(this).attr('id').split('-');
				
				var line=cs[1];
				var num=cs[2];
				
				awale.kik(line, num);
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
				backgroundPosition: -82*(qte-1)+'px 0'
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
		console.log(line+' '+num);
	}
	
	
	
};


