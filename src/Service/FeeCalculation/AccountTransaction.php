<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\{
    Interfaces\AccountTransactionAbstract,
    Interfaces\FeeCalculationInterface,
    WithdrawTransaction,
    BusinesWithdrawTransaction,
    PrivatWithdrawTransaction,
    DepositTransaction
};
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\Math\MathAbstract;


class AccountTransaction extends AccountTransactionAbstract
{
    private $transaction;
    private string $transactionType;
    private string $accountType;
    private FeeCalculationInterface $privateWithdraw;
    private FeeCalculationInterface $businesWithdraw;
    private FeeCalculationInterface $deposit;
    

    public function __construct(
        string $transactionType,
        string $accountType,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {
        $this->transactionType = $transactionType;
        $this->accountType = $accountType;
        $this->privateWithdraw = new PrivatWithdrawTransaction($exchange, $repository, $math);
        $this->businesWithdraw = new BusinesWithdrawTransaction($exchange, $repository, $math);                
        $this->deposit = new DepositTransaction($exchange, $repository, $math);
    }


    public function getTransaction()
    {
        switch ($this->transactionType) {
            case 'withdraw':
                if ($this->accountType === 'business') {
                    $this->transaction = $this->businesWithdraw;
                } else if ($this->accountType === 'private') {
                    $this->transaction = $this->privateWithdraw;
                }               
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
