<?php

namespace App\Service\FeeCalculation\Interfaces;

use App\Service\FeeCalculation\{Interfaces\WithdrawTransactionAbstract, Interfaces\DepositTransactionAbstract};
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


abstract class AccountTransactionAbstract 
{
    private $transaction;


    public function __construct(
        string $transactionType,
        string $accountType,
        ChangeMoneyInterface $change,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {}


    abstract public function getTransaction();
}
