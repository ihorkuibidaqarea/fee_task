<?php

$app = require_once __DIR__. '/../bootstrap/container.php';

use App\Service\UserFee\UserFeeAbstract;

$feeResponce = $app->get(UserFeeAbstract::class)->getFee();

foreach ($feeResponce as $fee) {
    echo $fee .' ';
}
