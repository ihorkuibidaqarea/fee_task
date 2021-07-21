<?php

require_once __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__. '/../bootstrap/container.php';

use App\Service\UserFee\UserFeeAbstract;


try {
    var_dump($app->get(UserFeeAbstract::class)->getFee());  
} catch (\Exception $e) {
    echo $e->getMessage();
} 
