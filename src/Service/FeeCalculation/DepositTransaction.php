<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use App\Service\Math\Math;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;
use App\Config\ConfigManager;


class DepositTransaction extends DepositTransactionAbstract implements FeeCalculationInterface
{
    private $feePercent;
    public $math;
    private $exchange;
    private $repository;


    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository, Math $math)
    {        
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->feePercent = ConfigManager::getConfig('deposit_fee');
        $this->math = $math; //new Math(self::SCALE);
    }


    public function fee($operation_date, $user_id, $user_type, $amount, $currency): string
    {                
        $fee = $this->math->multiply((string) $amount, $this->feePercent);
        return $fee;
    }
}
