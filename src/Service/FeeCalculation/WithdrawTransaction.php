<?php

declare(strict_types=1);

namespace Src\Service\FeeCalculation;

use Src\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use Src\Service\FeeCalculation\{
                                    BusinesWithdrawTransaction,
                                    PrivatWithdrawTransaction
                                };
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


class WithdrawTransaction implements FeeCalculationInterface
{

    private $exchange;
    private $repository;

    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {
        $this->exchange = $exchange;
        $this->repository = $repository;
    }
        
       
 
    public function fee(string $operation_date, $user_id, string $user_type, string $amount, string $currency)
    {
        switch ($user_type) {
            case 'business':
                $fee = (new BusinesWithdrawTransaction($this->exchange, $this->repository))
                       ->fee($operation_date, $user_id, $user_type, $amount, $currency);
                return $fee;                
            case 'private':
                $fee = (new PrivatWithdrawTransaction($this->exchange, $this->repository))
                       ->fee($operation_date, $user_id, $user_type, $amount, $currency);
                return $fee;           
            default:
                throw new \Exception('Unknown User Type'); 
        }
  
        throw new \Exception('Something went wrong with transaction'); 
    } 
    

}