<?php

namespace App\Service\FeeCalculation\Interfaces;


interface FeeCalculationInterface
{
    public function fee(
                        string $operation_date,
                        int $user_id, 
                        string $amount,
                        string $currency
                    );
}
