<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


abstract class DepositTransactionAbstract
{
    public function __construct(
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {}
}
