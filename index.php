<?php
//include the loader
require_once 'loader.php';

$plink = new Plink\Environment('examples', '.php');
echo $plink->render('home', array('date'=>date('l jS \of F Y')));
