<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;


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
