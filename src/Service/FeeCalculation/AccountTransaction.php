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

use Psr\Container\ContainerInterface;
use function DI\get;


class AccountTransaction extends AccountTransactionAbstract
{
    private $transaction;
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
        $rt = 78;
        switch ($this->transactionType) {
            case 'withdraw':
                $this->transaction = get(WithdrawTransaction::class);//($this->exchange, $this->repository);
                break;
            case 'deposit':
                $this->transaction =  new DepositTransaction($this->exchange, $this->repository);
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
