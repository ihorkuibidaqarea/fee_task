<?php

$app = require_once __DIR__. '/../bootstrap/container.php';

use App\Service\UserFee\UserFeeAbstract;
try {
    $app->get(UserFeeAbstract::class)->getFee();
} catch (\Exception $e) {
    echo $e->getMessage();
} 
