<?php

declare(strict_types=1);

namespace Src\Service\FeeCalculation;

use Src\Service\FeeCalculation\Interfaces\AccountTransactionAbstract;
use Src\Service\FeeCalculation\{
                                DepositTransaction,
                                WithdrawTransaction
                                };
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


class AccountTransaction extends AccountTransactionAbstract
{
    private FeeCalculationInterface $transaction;
    private string $transactionType;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;


    public function __construct($transactionType, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {
        $this->transactionType = $transactionType;
        $this->exchange = $exchange;
        $this->repository = $repository;
    }


    public function getTransaction()
    {
        switch ($this->transactionType) {
            case 'withdraw':
                $this->transaction =  new WithdrawTransaction($exchange, $repository);
                break;
            case 'deposit':
                $this->transaction =  new DepositTransaction($exchange, $repository);
                break;            
            default:
                throw new \Exception('Invalid Transaction Type'); 
        }
        return $this->transaction;
    }


    // public function __construct($operationType, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    // {        
    //     switch ($operationType) {
    //         case 'withdraw':
    //             $this->transaction =  new WithdrawTransaction($exchange, $repository);
    //             break;
    //         case 'deposit':
    //             $this->transaction =  new DepositTransaction($exchange, $repository);
    //             break;            
    //         default:
    //             throw new \Exception('Invalid Transaction Type'); 
    //     }
    // }
     
}
