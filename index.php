<?php
//include the loader
require_once 'loader.php';

$plink = new Plink\Environment('examples');
$plink->setLayout('layout.php');
echo $plink->render('home.php');