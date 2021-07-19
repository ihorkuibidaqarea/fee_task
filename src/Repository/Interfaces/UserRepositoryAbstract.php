<?php

namespace Src\Repository\Interfaces;

abstract class UserRepositoryAbstract
{

    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }


    public function getUserWithdravals(int $user_id ){}
    

    public function setUserWithdravals(int $user_id, string $transaction_date, string $amount, string $currency) {}

}