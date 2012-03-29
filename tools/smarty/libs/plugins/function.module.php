<?php

function smarty_function_module($params) {
	if(isset($params['name'])) {
		$name=$params['name'];
		
		$ret='';
		
		
		
		$ret.='
			<div id="'.$name.'">
		';
		
		// inclure JS si ya
		if(file_exists(DIR_MODULES.$name.'/'.$name.'.js')) {
			$ret.='
				<script type="text/javascript" src="'.WWW_MODULES.$name.'/'.$name.'.js"></script>
			';
		}
		
		// inclure CSS si ya
		if(file_exists(DIR_MODULES.$name.'/'.$name.'.css')) {
			$ret.='
				<link rel="stylesheet" type="text/css" href="'.WWW_MODULES.$name.'/'.$name.'.css" />
			';
		}
		
		// place code JS de chargement asynchrone
		$ret.='
				<script type="text/javascript">
					$(function () {
						'.$name.'_init();
					});
				</script>
			</div>
		';
		
		return $ret;
	} else return 'NULL';
}


