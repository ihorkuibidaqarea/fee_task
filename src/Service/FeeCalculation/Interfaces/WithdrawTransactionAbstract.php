<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;


abstract class WithdrawTransactionAbstract
{
    public function __construct(ChangeMoneyInterface $change, UserRepositoryAbstract $repository)
    {}
}
