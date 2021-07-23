<?php

declare(strict_types=1);

namespace App\Entity;

class Operaiton
{
    public string $date;
    public $userId;
    public string $accountType;
    public string $operation;
    public string $amount;
    public string $currency;
     

    public function __construct(string $date, $userId, string $accountType, string $operation, string $amount, string $currency)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->accountType = $accountType;
        $this->operation = $operation;
        $this->amount = $amount;
        $this->currency = $currency;
    }


    public function getDate(): string
    {
        return $this->date;
    }


    public function getUserId(): int
    {
        return (integer) $this->userId;
    }


    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getOperationName(): string
    {
        return $this->operation;
    }


    public function getAmount(): string
    {
        return $this->amount;
    }


    public function getCurrency(): string
    {
        return $this->currency;
    }
}
