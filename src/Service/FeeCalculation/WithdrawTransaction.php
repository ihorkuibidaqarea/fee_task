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
use App\Service\Math\Math;


class WithdrawTransaction extends WithdrawTransactionAbstract implements FeeCalculationInterface
{
    private $exchange;
    private $repository;
    private $math;
    private $withdrawalClient;


    public function __construct(
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        Math $math
    ) {
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = $math;
    }        
       
 
    public function fee(string $operation_date, $user_id, string $user_type, string $amount, string $currency)
    {
        switch ($user_type) {
            case 'business':
                $fee = (new BusinesWithdrawTransaction($this->exchange, $this->repository, $this->math))->fee($operation_date, $user_id, $user_type, $amount, $currency);
                return $fee;                
            case 'private':
                $fee = (new PrivatWithdrawTransaction($this->exchange, $this->repository, $this->math))->fee($operation_date, $user_id, $user_type, $amount, $currency);
                return $fee;           
            default:
                throw new \Exception('Unknown User Type'); 
        }  
        throw new \Exception('Something went wrong with transaction'); 
    }
}
