<?php

declare(strict_types=1);

namespace Src\Service\FeeCalculation;

use Src\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use Src\Service\Math\Math;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;

class DepositTransaction implements FeeCalculationInterface {

    private const FEE  = '0.0003';
    private const SCALE  = 5;
    public $math;
    private $exchange;

    
    public function __construct(ChangeMoneyInterface $exchange)
    {        
        $this->exchange = $exchange;
        $this->math = new Math(self::SCALE);
    }


    public function fee($operation_date, $user_id, $user_type, $amount, $currency)
    {                
        $fee = $this->math->multiply( (string) $amount, self::FEE );
        return $fee;
    }
        
}