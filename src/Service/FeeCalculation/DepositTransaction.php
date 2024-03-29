<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use App\Service\Math\MathAbstract;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;
use App\Config\ConfigManager;


class DepositTransaction extends DepositTransactionAbstract implements FeeCalculationInterface
{
    private string $feePercent;
    public MathAbstract $math;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;


    public function __construct(
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->feePercent = ConfigManager::get('deposit_fee');
        $this->math = $math;
    }


    public function fee(string $operationDate, int $userId, string $amount, string $currency): string
    {                
        return $this->math->multiply((string) $amount, $this->feePercent);        
    }
}
