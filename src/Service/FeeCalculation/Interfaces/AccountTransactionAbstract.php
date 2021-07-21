<?php

namespace Src\Service\FeeCalculation\Interfaces;

use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;
use Src\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;


abstract class AccountTransactionAbstract 
{
    private $transaction;


    public function __construct(
        $operation_type,
        ChangeMoneyInterface $change,
        UserRepositoryAbstract $repository,
        WithdrawTransactionAbstract $withdraw
    ) {}
}
