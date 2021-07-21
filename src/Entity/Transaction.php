<?php

declare(strict_types=1);

namespace App\Entity;

class Transaction
{
    public $date;
    public $amount;
    public $currency;
     

    public function __construct($date, $amount, $currency)
    {
        $this->date = $date;
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
