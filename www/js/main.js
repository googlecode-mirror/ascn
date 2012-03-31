

$(function() {
	Page.overrideAjaxButton();
	Page.ajaxLoad(Page.hash());
});






$(window).hashchange(function() {
	Page.ajaxLoad(Page.hash());
});






/**
 * Modules
 */
var Modules = {
		
	refresh: function(module_name, callback) {
		$.ajax({
			url: 'modules/'+module_name+'/index.php',
			success: function(r) {
				$('#'+module_name).html(r);
				callback && callback();
				Page.overrideAjaxButton();
			}
		});
	},
	
	
	
	action: function(module_name, action, data) {
		if(arguments.length<2) {
			Modules.refresh(module_name);
			return;
		}
		
		if(!data) var data=new Object();
		
		data.appli_type='module';
		data.appli_name=module_name;
		data.appli_action=action;
		
		$.post('action.php', data, function (r) {
			Modules.result(module_name, action, r);
		});
	},
	
	
	
	result: function(module_name, action, r) {
		
		if(r) {
			try {
				r=JSON.parse(r);
				
				if(r.has_error) {
					ajaxError(r.errors);
				}
			} catch(e) {
				console.groupCollapsed('www/main.js : ajaxResult() : JSON error. Data received : ...');
				console.log(r);
				console.groupEnd();
				r=null;
			}
		} else r=null;
		
		
		var fx=window[module_name]['ajax_'+action];
		
		
		try {
			window[module_name]['ajax_'+action](r);
		} catch(e) {
			try {
				window['Modules']['ajax_'+action](r);
			} catch(e) {
				console.log('JS function '+module_name+'.ajax_'+action+'() non trouvee...');
				console.log('JS function Modules.ajax_'+action+'() non trouvee...');
			}
		}
	}
	
	
}





/**
 * Jeu
 */
var Jeux = {
		
	refresh: function(jeu_name, callback) {
		$.ajax({
			url: 'games/'+jeu_name+'/index.php',
			success: function(r) {
				$('#'+jeu_name).html(r);
				callback && callback();
				overrideAjaxButton();
			}
		});
	},
	
	
	
	action: function(jeu_name, action, data) {
		if(arguments.length<2) {
			Jeux.refresh(jeu_name);
			return;
		}
		
		if(!data) var data=new Object();
		
		data.appli_type='jeu';
		data.appli_name=jeu_name;
		data.appli_action=action;
		
		$.post('action.php', data, function (r) {
			Jeux.result(jeu_name, action, r);
		});
	},
	
	
	
	result: function(jeu_name, action, r) {
		
		if(r) {
			try {
				r=JSON.parse(r);
				
				if(r.has_error) {
					ajaxError(r.errors);
				}
			} catch(e) {
				console.groupCollapsed('www/main.js : ajaxResult() : JSON error. Data received : ...');
				console.log(r);
				console.groupEnd();
				r=null;
			}
		} else r=null;
		
		
		try {
			window[jeu_name]['ajax_'+action](r);
		} catch(e) {
			try {
				window['Jeux']['ajax_'+action](r);
			} catch(e) {
				console.log('JS function '+jeu_name+'.ajax_'+action+'() non trouvee...');
				console.log('JS function Jeux.ajax_'+action+'() non trouvee...');
			}
		}
	},
	
	
	
	
	ajax_creer_partie: function(r) {
		if(r.hasError) {
			lightbox.show('Erreur lors de la cr&eacute;ation de partie', '...');
		} else {
			Page.hash('games/'+r.jeu.name+'?partie='+r.partie.id);
		}
	}
}





/**
 * Page
 */
var Page = {


	overrideAjaxButton: function() {
		
		// ajaxload : charger le contenu d'une page
		$('a.ajaxload')
			.click(function () {
				Page.hash($(this).attr('href'));
				
				return false;
			})
			.removeClass('ajaxload');
		
		
		$('form.ajaxload')
			.submit(function () {
				var data=new Object();
				
				var inputs=$(this).find('input').each(function () {
					var input=$(this);
					
					var name=input.attr('name');
					var value=input.val();
					
					data[name]=value;
				});
				
				ajaxLoad($(this).attr('action'), data);
				
				return false;
			})
			.attr('method', 'post')
			.removeClass('ajaxload');
		
		
		
		
		// ajaxaction : requete qui retourne du json
		$('a.ajaxaction')
			.click(function () {
				var a=$(this).attr('href').split('/');
				
				if(a.length<3) {
					console.log('Erreur lien href : '+a[0]);
					return false;
				}
				
				switch(a[0]) {
					case 'modules':	Modules.action(a[1], a[2]);break;
					case 'games':	Jeux.action(a[1], a[2]);break;
				}
				
				
				return false;
			})
			.removeClass('ajaxaction');
		
		
		$('form.ajaxaction')
			.submit(function () {
				var a=$(this).attr('action').split('/');
				
				if(a.length<3) {
					console.log('Erreur form action : '+a[0]);
					return false;
				}
				
				var data=new Object();
				
				var inputs=$(this).find('input').each(function () {
					var input=$(this);
					
					var name=input.attr('name');
					var value=input.val();
					
					data[name]=value;
				});
				
				
				switch(a[0]) {
					case 'modules':	Modules.action(a[1], a[2], data);break;
					case 'games':	Jeux.action(a[1], a[2], data);break;
				}
				
				return false;
			})
			.attr('method', 'post')
			.removeClass('ajaxaction');
	},
	
	

	ajaxLoad: function(hash, data) {
		if(hash) {

			if(!data) var data=new Object();
			
			data.ajax='';
			
			$.ajax({
				url: hash,
				data: data,
				success: function(r) {
					$('#body').html(r);
					Page.overrideAjaxButton();
				},
				error: function(r) {
					lightbox.show('Oups !', '<p>Ceci est un lien mort...</p>');
				}
			});
		}
	},
	
	hash: function(h) {
		h && (window.location.hash=h);
		return window.location.hash.substring(1);
	},



	addCss: function(name, file) {
		$('head').append('<link class="dyncss_'+name+'" rel="stylesheet" type="text/css" href="'+file+'" />');
	},
	
	removeCss: function(name) {
		$('head .dyncss_'+name).remove();
	}
}






