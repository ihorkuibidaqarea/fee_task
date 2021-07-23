<?php

$app = require_once __DIR__. '/../bootstrap/container.php';

use App\Service\UserFee\UserFeeAbstract;

$app->get(UserFeeAbstract::class)->getFee();

