<?php

declare(strict_types=1);

namespace Src\Entity;

class Operaiton
{
    public $date;
    public $userId;
    public $accountType;
    public $operation;
    public $amount;
    public $currency;
     

    public function __construct($date, $userId, $accountType, $operation, $amount, $currency)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->accountType = $accountType;
        $this->operation = $operation;
        $this->amount = $amount;
        $this->currency = $currency;
    }


    public function getDate()
    {
        return $this->date;
    }


    public function getUserId()
    {
        return $this->userId;
    }


    public function getAccountType()
    {
        return $this->accountType;
    }

    public function getOperationName()
    {
        return $this->operation;
    }


    public function getAmount()
    {
        return $this->amount;
    }


    public function getCurrency()
    {
        return $this->currency;
    }
}
