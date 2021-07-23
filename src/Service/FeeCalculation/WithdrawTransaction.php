<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\{
    Interfaces\FeeCalculationInterface,
    Interfaces\WithdrawTransactionAbstract,
    BusinesWithdrawTransaction,
    PrivatWithdrawTransaction
};
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


class WithdrawTransaction extends WithdrawTransactionAbstract implements FeeCalculationInterface
{
   private $accountTypeTransaction;

    public function __construct(
        string $accountType,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {
        switch ($accountType) {
            case 'business':
                $this->accountTypeTransaction = new BusinesWithdrawTransaction($exchange, $repository, $math);
                break;                
            case 'private':
                $this->accountTypeTransaction = new PrivatWithdrawTransaction($exchange, $repository, $math);
                break;           
            default:
                throw new \Exception('Unknown User Type'); 
        }  
    }        
       
 
    public function fee(string $operationDate, int $userId, string $amount, string $currency): string
    {
        return $this->accountTypeTransaction->fee($operationDate, $userId, $amount, $currency);              
    }
}
