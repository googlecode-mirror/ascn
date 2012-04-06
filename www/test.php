<?php
require_once '../config.php';
require_once DIR_GAMES.'tictactoe/TicTacToe.php';

$grille=array(
	1,2,2,
	1,1,2,
	0,2,1
);

print_r(TicTacToe::checkTicTacToe($grille));