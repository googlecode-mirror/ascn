<?php
require_once '../config.php';

querySimple('
	truncate table partie;
	truncate table slot;
	delete from joueur where joueur_invite=1;
');