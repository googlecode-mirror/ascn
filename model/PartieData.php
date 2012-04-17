<?php




class PartieData {
	
	
	public static function getFromData($data_type) {
		$data=new $data_type();
		$data->initFromArray(partie()->data_obj->$data_type);
		return $data;
	}
	
	public function initFromArray($array) {
		foreach($array as $key=>$value) {
			$this->$key=$value;
		}
	}
	
}