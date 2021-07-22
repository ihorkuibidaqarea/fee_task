<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\{
    Interfaces\AccountTransactionAbstract,
    WithdrawTransaction,
    DepositTransaction
};
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


class AccountTransaction extends AccountTransactionAbstract
{
    private $transaction;
    

    public function __construct(
        string $transactionType,
        string $accountType,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {
        switch ($transactionType) {
            case 'withdraw':
                $this->transaction = new WithdrawTransaction($accountType, $exchange, $repository, $math);                
                break;
            case 'deposit':                
                $this->transaction = new DepositTransaction($accountType, $exchange, $repository, $math);          
                break;            
            default:
                throw new \Exception('Invalid Transaction Type'); 
        }
    }


    public function getTransaction()
    {
        return $this->transaction;
    }     
}
