

$(function () {
	overrideAjaxButton();
});


/**
 * 
 * Vérifie les classes de l'élément et return une application si
 * une classe correspond au format 'appli-A:B' ou A='jeu|module'
 * 
 * @param item DOMObject
 * @returns 'A:B'|null
 */
function getAppliFromClasses(item) {
	var classes=item.attr('class').split(' ');
	
	for(var i=0;i<classes.length;i++) {
		var classe=$.trim(classes[i]);
		var arr=classe.split('-');
		if(arr.length==2 && arr[0]=='appli') {
			appli=arr[1];
			arr=appli.split(':');
			if(arr.length==2 && (arr[0]=='module' || arr[0]=='jeu')) {
				return appli;
			}
		}
	}
	
	return null;
}








/**
 * 
 * Edite l'action lors des cliques sur les lien a:href
 * 		et envois de formulaires en ajax.
 * 
 * @param String selector ou executer la fonction.
 * Exemple pour overrider seulement les boutons d'un module :
 * overrideAjaxButton('#quickjoin');
 * 
 * Par defaut vide, et fait toute la page.
 */
function overrideAjaxButton(selector) {
	if(arguments.length<1) {
		selector='';
	}
	
	// Formulaires ajax :
	$(selector+' form.ajax')
		.unbind('submit')
		.submit(function () {
			var data=new Object();
			
			var inputs=$(this).find('input').each(function () {
				var input=$(this);
				
				var name=input.attr('name');
				var value=input.val();
				
				data[name]=value;
			});
			
			var appli=getAppliFromClasses($(this))
			var action=$(this).attr('action');
			
			ajaxAction(appli, action, data);
			
			return false;
	});
	
	
	// Lien a/href ajax :
	$(selector+' a.ajax')
		.unbind('click')
		.click(function () {
			var data=new Object();
			
			var appli=getAppliFromClasses($(this));
			var action=$(this).attr('href');
			
			ajaxAction(appli, action, data);
			
			return false;
	});
	
	
	
	
	$(selector+' form.redirect')
		.unbind('submit')
		.submit(function () {
			
			var data=Object();
			
			var inputs=$(this).find('input').each(function () {
				var input=$(this);
				
				var name=input.attr('name');
				var value=input.val();
				
				data[name]=value;
			});
			
			
			ajaxRedirect($(this).attr('action'), data);
			
			return false;
	});
	
	$(selector+' a.redirect')
		.unbind('click')
		.click(function () {
			ajaxRedirect($(this).attr('href'));
			
			return false;
	});

}







/**
 * 
 * @param appli String ex : 'jeu:TicTacToe'
 * @param action String ex : 'update'
 * @param data Object javascript contenant les key=>value
 * 
 * En PHP, TicTacToe::ajax_update() sera appelée.
 * Les parametres seront récupérés en PHP par getValue('user');
 * 
 * Les données JSON reponse de la fonction PHP seront
 * interpretee en JS dans : ajax_tictactoe_update(res);
 * 
 */
function ajaxAction(appli, action, data) {
	if(arguments.length<3 || !data) {
		data=new Object();
	}
	
	data.appli=appli;
	data.action=action;
	
	for(var i in Value) {
		data['param_'+i]=data[A[i]];
	}
	
	if(window.slot_id) {
		data.slot=slot_id;
	}
	
	
	$.post(www_root+'action.php', data, function(res) {
		ajaxResult(appli.split(':')[1], action, res);
	});
}







/**
 * 
 * Lors de la reception des données ajax,
 * la fonction JS ajax_APPLI_ACTION(resultat) est appellée.
 * elle doit être déclarée dans le fichier JS dans le dossier de l'appli.
 * 
 * @param action
 * @param resultat
 */
function ajaxResult(appli, action, resultat) {
	var fxname='ajax_'+appli.toLowerCase()+'_'+action;
	
	if(resultat) {
		try {
			resultat=JSON.parse(resultat);
			
			if(resultat.has_error) {
				ajaxError(resultat.errors);
			}
		} catch(e) {
			console.groupCollapsed('www/main.js : ajaxResult() : JSON error. Data received : ...');
			console.log(resultat);
			console.groupEnd();
			resultat=null;
		}
	} else resultat=null;
	
	
	
	if(typeof window[fxname] == 'function')
		window[fxname](resultat);
	else
		console.log('JS function '+fxname+'() non trouvee...');
}




function ajaxRedirect(page, data, callback) {
	var params;
	if(page.split('?').length>1) params='&';
	else params='?';
	
	params+='ajax=1';
	
	data && $.each(data, function(key, value) {
		params+='&'+key+'='+value;
	});
	
	$.post(www_root+page+params, function(res) {
		$('#body').html(res);
		callback && callback();
		overrideAjaxButton('#body');
	});
}



function ajaxRefresh(callback) {
	$.post(window.location.href, 'ajax=1', function(res) {
		$('#body').html(res);
		callback && callback();
		overrideAjaxButton('#body');
	});
}





/**
 * 
 * @param String ex : module_refresh('quickjoin');
 * Va juste rafraichir le module en mettant le flux de run() dans sa div.
 */
function module_refresh(module_name, callback) {
	var nb_args=arguments.length;
	$.post(www_modules+module_name+'/index.php', Value, function(res) {
		$('#'+module_name).html(res);
		overrideAjaxButton('#'+module_name);
		if(nb_args>1) {
			callback();
		}
	});
	
}




function ajaxError(errors) {
	var s='Erreur(s) lors d\'une requête :\n';
	for(var i=0;i<errors.length;i++) {
		s+=' - '+errors[i]+"\n";
	}
	alert(s);
}




/*
 * 
 * Tools
 * 
 */



var hashes=null;

function hash(key, value) {
	if(hashes==null) {
		hashes=new Object();
		
		var params=window.location.hash.split('#');
		
		for(var i=1;i<params.length;i++) {
			var keyvalue=params[i].split(':');
			hashes[keyvalue[0]]=keyvalue[1];
		}
	}

	
	if(arguments.length>1) {
		hashes[key]=value;
		
		var s='';
		$.each(hashes, function(key, value) {
			if(value!='delete_hash') {
				s+='#'+key+':'+value;
			}
		});
		window.location.hash=s;
	}
	
	if(value!='delete_hash')
		return hashes[key];
}


function removeHash(key) {
	hash(key, 'delete_hash');
}



function addCss(name, file) {
	$('head').append('<link class="dyncss_'+name+'" rel="stylesheet" type="text/css" href="'+file+'" />');
}
function removeCss(name) {
	$('head .dyncss_'+name).remove();
}

function addJs(name, file) {
	$('head').append('<script class="dynjs_'+name+'" src="'+file+'" type="text/javascript"></script>');
}
function removeJs(name) {
	$('head .dynjs_'+name).remove();
}









