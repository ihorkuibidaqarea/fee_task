<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


abstract class WithdrawTransactionAbstract
{
    public function __construct(string $accountType, ChangeMoneyInterface $change, UserRepositoryAbstract $repository, MathAbstract $math)
    {}
}
