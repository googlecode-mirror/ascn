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
			<div id="content">
				<div id="header">
				
					<h1>{$site_name}</h1>
					<a href="index.php" class="ajaxload">Home</a>
					{module name='userview'}
					
				</div>
				<div id="body">
				
					{$CONTENT}
					
				</div>
				{*module name='quickjoin'*}
			</div>
		</div>
		{module name='lightbox'}
	</body>
</html>