<?php

declare(strict_types=1);

namespace Src\Entity;

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
