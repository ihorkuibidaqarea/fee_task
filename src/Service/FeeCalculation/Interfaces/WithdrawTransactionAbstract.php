<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\Math;


abstract class WithdrawTransactionAbstract
{
    public function __construct(ChangeMoneyInterface $change, UserRepositoryAbstract $repository, Math $math)
    {}
}
