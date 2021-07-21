<?php

namespace Src\Service\FeeCalculation\Interfaces;

use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


abstract class DepositTransactionAbstract
{
    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {}
}
