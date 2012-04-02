


var lightbox = {
	
	init: function() {
		Modules.refresh('lightbox', function() {
			$('#lightbox').hide();
			$('.lightbox-close')
				.css('cursor', 'pointer')
				.click(function () {
					lightbox.hide();
				});
			Page.addCss('css_lightbox', 'modules/lightbox/lightbox.css');
		});
	},
	
	
	
	show: function(title, content) {
		if(lightbox.isVisible()) {
			alert(title+' : '+content);
		} else {
			$('#lightbox .lightbox-title').html(title);
			$('#lightbox .lightbox-content').html(content);
			Page.overrideAjaxButton();
			$('#lightbox').fadeIn('fast');
		}
	},
	
	
	
	hide: function() {
		$('#lightbox').fadeOut('fast');
	},
	
	
	isVisible: function() {
		return $('#lightbox').is(":visible");
	}

};





