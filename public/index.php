<?php

require_once __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__. '/../bootstrap/container.php';

use Src\Service\UserFee\UserFee;


try {   

    $userFee = $app->get(UserFee::class);                 
    var_dump($UserFee->getFee());    

} catch (\Exception $e) {
    echo $e->getMessage();
} 
