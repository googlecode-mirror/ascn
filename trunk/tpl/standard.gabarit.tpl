<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{$page->title}</title>
		
		{foreach from=$page->css_files item=css_file}
		<link rel="stylesheet" type="text/css" href="{$css_file}" />
		{/foreach}
		
		{foreach from=$page->js_files item=js_file}
		<script src="{$js_file}" type="text/javascript"></script>
		{/foreach}
		
		
		
	</head>
	<body>
		<div id="js_append"></div>
		<div id="wrapper">
			<div id="wrapper2">
				<div id="content">
					<div id="header">
					
						
					</div>
					<div id="gadget">
						<div class="log-container">
							<div class="log">
								<div class="log-icon log-right"><img src="img/log-right.png" alt="loggin-left" /></div>
								<div class="log-content" style="width: 0px">
									{module name='userview'}
								</div>
								<div class="log-icon log-center"><img src="img/log-center.png" alt="loggin-left" /></div>
								<div class="log-icon log-left"><img src="img/log-left.png" alt="loggin-left" /></div>
							</div>
						</div>
						<div class="nav-btn-container clearfix">
							<div class="nav-btn home" title="Accueil"><div class="icon"></div></div>
							<div class="nav-btn explorer" title="Explorateur de jeux"><div class="icon"></div></div>
							<div class="nav-btn user" title="Mon Compte"><div class="icon"></div></div>
							<div class="nav-btn help" title="Aide"><div class="icon"></div></div>
						</div>
						<div class="menus-container">
							<div class="menu">
								{module name='quickjoin'}
							</div>
						</div>
					</div>
					<div id="body">
					
						{$CONTENT}
						
					</div>
					
				</div>
			</div>
		</div>
		{module name='lightbox'}
	</body>
</html>