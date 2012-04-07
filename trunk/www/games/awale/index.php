<?php
require_once '../../../config.php';
env()->initJeu(basename(__DIR__));
jeu()->run();