<?php
require_once '../config.php';

$appli_type=getValue('appli_type', false);
$appli_name=getValue('appli_name', false);
$appli_action=getValue('appli_action', false);

if($appli_type && $appli_name && $appli_action) {
	
	require_once appli_dir($appli_type).'/'.$appli_name.'/'.$appli_name.'.php';
	
	$appli=new $appli_name();
	
	$r=$appli->{'ajax_'.$appli_action}();
	
	if(!is_null($r))
		print json_encode($r);
}