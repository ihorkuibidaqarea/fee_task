<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\AccountTransactionAbstract;
use App\Service\FeeCalculation\{
                                DepositTransaction,
                                WithdrawTransaction
                                };
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;
use App\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;


class AccountTransaction extends AccountTransactionAbstract
{
    private $transaction;
    private string $transactionType;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;
    private WithdrawTransactionAbstract $withdraw;
    private DepositTransactionAbstract $deposit;
    

    public function __construct(
        $transactionType,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        WithdrawTransactionAbstract $withdraw,
        DepositTransactionAbstract $deposit
    ) {
        $this->transactionType = $transactionType;
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->withdraw = $withdraw;
        $this->deposit = $deposit;
    }


    public function getTransaction()
    {
        switch ($this->transactionType) {
            case 'withdraw':
                $this->transaction = $this->withdraw;
                break;
            case 'deposit':
                $this->transaction = $this->deposit;
                break;            
            default:
                throw new \Exception('Invalid Transaction Type'); 
        }
        return $this->transaction;
    }     
}
