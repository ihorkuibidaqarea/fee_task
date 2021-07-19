<?php

declare(strict_types=1);

namespace Src\Entity;

class ExchangeResponse {

   
    public $success;
    public $amount;
     

    public function __construct($success, $amount)
    {
        $this->success = $success;
        $this->amount = $amount;
    }

}