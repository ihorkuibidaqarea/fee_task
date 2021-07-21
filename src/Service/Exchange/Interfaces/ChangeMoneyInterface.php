<?php

namespace App\Service\Exchange\Interfaces;


interface ChangeMoneyInterface
{
    public function moneyExchange(string $amount, string $currency);

    
    public function reverseMoneyExchange(string $amount, string $currency);
}
