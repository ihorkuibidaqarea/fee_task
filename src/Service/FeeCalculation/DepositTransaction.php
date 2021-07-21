<?php

declare(strict_types=1);

namespace Src\Service\FeeCalculation;

use Src\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use Src\Service\Math\Math;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;
use Src\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;


class DepositTransaction extends DepositTransactionAbstract implements FeeCalculationInterface
{
    private const FEE  = '0.0003';
    private const SCALE  = 5;
    public $math;
    private $exchange;
    private $repository;


    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {        
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = new Math(self::SCALE);
    }


    public function fee($operation_date, $user_id, $user_type, $amount, $currency): string
    {                
        $fee = $this->math->multiply((string) $amount, self::FEE);
        return $fee;
    }
}
