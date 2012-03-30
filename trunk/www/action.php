<?php
require_once '../config.php';


if(($module_name=getValue('module_name', false)) && ($module_action=getValue('module_action', false))) {
	
	require_once DIR_MODULES.$module_name.'/'.$module_name.'.php';
	
	$module=new $module_name();
	
	$r=$module->{'ajax_'.$module_action}();
	
	if(!is_null($r))
		print json_encode($r);
}