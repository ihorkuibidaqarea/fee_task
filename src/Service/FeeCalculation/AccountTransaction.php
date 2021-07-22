<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\{
    Interfaces\AccountTransactionAbstract,
    Interfaces\WithdrawTransactionAbstract,
    Interfaces\DepositTransactionAbstract,
    DepositTransaction,
    WithdrawTransaction
};
use App\Config\ConfigManager;


class AccountTransaction extends AccountTransactionAbstract
{
    private $transaction;
    

    public function __construct(
        $transactionType,
        WithdrawTransactionAbstract $withdraw,
        DepositTransactionAbstract $deposit
    ) {
        switch ($transactionType) {
            case 'withdraw':
                $this->transaction = $withdraw;
                break;
            case 'deposit':
                $this->transaction = $deposit;
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
