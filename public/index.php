<?php

require_once '../vendor/autoload.php';

require_once '../config/settings.php';

require_once '../src/slim-init.php';
require_once '../src/routes.php';


/******************************/
/********* START MAGIC ********/
$app->run();
/******************************/
