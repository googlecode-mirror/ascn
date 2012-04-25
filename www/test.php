<?php
require_once '../config.php';
//require_once DIR_GAMES.'awale/Awale.php';

$a=new Coords(4, 5);
$b=new Coords(5, 4);

print $a;
print $b;


print Coords::milieu($a->x, $a->y, $b->x, $b->y);

var_dump(Coords::memeDiagonale($a->x, $a->y, $b->x, $b->y));

