<?php
//include the loader
require_once 'SimpleTemplateEngine/loader.php';

$env = new SimpleTemplateEngine\Environment('examples', '.php');
echo $env->render('home', ['date'=>date('l jS \of F Y')]);
