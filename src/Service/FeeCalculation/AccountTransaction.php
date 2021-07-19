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

class AccountTransaction extends AccountTransactionAbstract {

    public $transaction;
   
    public function __construct($operation_type, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {        
        switch ($operation_type) {
            case 'withdraw':
                $this->transaction =  new WithdrawTransaction($exchange, $repository);
                break;
            case 'deposit':
                $this->transaction =  new DepositTransaction($exchange, $repository);
                break;            
            default:
                throw new \Exception('Invalid Transaction Type'); 
        }
    }
     
}
