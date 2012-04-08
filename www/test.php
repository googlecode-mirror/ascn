<?php
require_once '../config.php';
require_once DIR_GAMES.'tictactoe/TicTacToe.php';

print_r(queryTab('select * from jeu order by jeu_id desc limit 1'));

$j=new DBItem('jeu', 2);
print_r($j->title);