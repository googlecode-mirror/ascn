<?php
require_once '../config.php';
require_once DIR_GAMES.'awale/Awale.php';

$j=new Awale();


print_r($j->getOptionsValues());