<?php

declare(strict_types=1);

namespace App\Entity;

class ExchangeResponse
{
    public $amount;
    public $success;


    public function __construct(string $amount, bool $success = true)
    {
        $this->amount = $amount;
        $this->success = $success;        
    }
}
