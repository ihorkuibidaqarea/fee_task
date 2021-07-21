<?php

require_once __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__. '/../bootstrap/container.php';


use Src\Service\UserFee\UserFeeAbstract;
use Src\Service\UserFee\UserFee;


try {
    var_dump($app->get(UserFeeAbstract::class)->getFee());  
} catch (\Exception $e) {
    echo $e->getMessage();
} 
