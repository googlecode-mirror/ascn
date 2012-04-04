<?php
require_once '../config.php';

querySimple('
	truncate table partie;
	truncate table slot;
');