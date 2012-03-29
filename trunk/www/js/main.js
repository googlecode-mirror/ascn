

$(function() {
	overrideAjaxButton();
	ajaxLoad(hash());
});


function hash(h) {
	h && (window.location.hash=h);
	return window.location.hash;
}


function ajaxLoad(hash) {
	if(hash) {
		$.ajax({
			url: hash.substring(1),
			data: 'ajax',
			success: function(r) {
				$('#body').html(r);
				overrideAjaxButton();
			}
		});
	}
}



$(window).hashchange(function() {
	ajaxLoad(hash());
});







function overrideAjaxButton() {
	
	$('a.ajaxload')
		.click(function () {
			hash($(this).attr('href'));
			
			return false;
		})
		.removeClass('ajaxload');

}











