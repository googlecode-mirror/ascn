<?php
require_once '../config.php';

$slot=new Slot(1);

var_dump($slot->score);
$slot->addScore(5);
var_dump($slot->score);
$slot->addScore(2.5);
var_dump($slot->score);
$slot->addScore(1/3);
var_dump($slot->score);
$slot->save();