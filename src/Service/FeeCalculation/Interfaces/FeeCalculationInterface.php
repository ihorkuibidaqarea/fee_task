<?php

namespace App\Service\FeeCalculation\Interfaces;


interface FeeCalculationInterface
{
    public function fee(string $operationDate, int $userId, string $amount, string $currency): string;
}
