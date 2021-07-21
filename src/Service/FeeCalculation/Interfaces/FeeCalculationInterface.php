<?php

namespace App\Service\FeeCalculation\Interfaces;


interface FeeCalculationInterface
{
    public function fee(
                        string $operation_date,
                        $user_id, 
                        string $user_type, 
                        string $amount,
                        string $currency
                    );
}
