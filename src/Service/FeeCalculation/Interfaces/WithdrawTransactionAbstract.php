<?php

namespace Src\Service\FeeCalculation\Interfaces;

use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


abstract class WithdrawTransactionAbstract
{
    public function __construct(ChangeMoneyInterface $change, UserRepositoryAbstract $repository)
    {}
}
