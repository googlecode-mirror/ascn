<?php



class Test extends Jeu {
	
	
	public function process() {
		// var_dump(partie()->getOptions());
		smarty()->assign('option', partie()->option('option_test'));
	}
	
	
	public function getInitialData() {
	}
	
	public function getOptions() {
		return array(
			'option_test' => new Option('Option test',
				array(
					array(
						'key'	=> 1,
						'value'	=> 'value 1',
					),
					array(
						'key'	=> 2,
						'value'	=> 'value 2',
						'default'	=> true,
					),
					array(
						'key'	=> 3,
						'value'	=> 'value 3',
					),
				)
			),
			'option_test_2' => new Option('Option test 2',
				array(
					array(
						'key'	=> 1,
						'value'	=> 'value 1',
					),
					array(
						'key'	=> 2,
						'value'	=> 'value 2',
					),
				)
			),
		);
	}
	
	
	
}