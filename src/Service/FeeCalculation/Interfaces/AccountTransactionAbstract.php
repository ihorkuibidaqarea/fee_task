<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\FeeCalculation\{Interfaces\WithdrawTransactionAbstract, Interfaces\DepositTransactionAbstract};


abstract class AccountTransactionAbstract 
{
    private $transaction;


    public function __construct(
        $operation_type,
        WithdrawTransactionAbstract $withdraw,
        DepositTransactionAbstract $deposit
    ) {}


    abstract public function getTransaction();
}
