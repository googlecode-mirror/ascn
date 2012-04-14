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
					
						{module name='userview'}
						
					</div>
					<div id="gadget">
						<div class="nav-btn-container">
							<div class="nav-btn home"><div class="icon"></div></div>
							<div class="nav-btn explorer"><div class="icon"></div></div>
							<div class="nav-btn user"><div class="icon"></div></div>
							<div class="nav-btn help"><div class="icon"></div></div>
						</div>
					</div>
					<div id="body">
					
						{$CONTENT}
						
					</div>
					{module name='quickjoin'}
				</div>
			</div>
		</div>
		{module name='lightbox'}
	</body>
</html>