

// Actualizer=function(url, data, callback, timeout, dontstartnow);


$(function () {
	setInterval(updateOrganizeRequest, 2000);
});


function updateOrganizeRequest() {
	var data=Page.getValue();
	Modules.action('partiemgr', 'updateOrganize', data);
}


Modules.ajax_updateOrganize=function(r) {
	var liste=$('.liste-joueurs');
	
	liste.html('');
	for(var i=0;i<r.slots.length;i++) {
		liste.append('<li>'+r.slots[i].joueur_pseudo+'</li>')
	}
};

