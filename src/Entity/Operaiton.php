<?php

declare(strict_types=1);

namespace Src\Entity;

class Operaiton {

    public $date;
    public $user_id;
    public $account_type;
    public $transaction;
    public $amount;
    public $currency;
     

    public function __construct( $date, $user_id, $account_type, $transaction, $amount, $currency ){

        $this->date = $date;
        $this->user_id = $user_id;
        $this->account_type = $account_type;
        $this->transaction = $transaction;
        $this->amount = $amount;
        $this->currency = $currency;

    }



    public function getDate(){

        return $this->date;

    }


    public function getUserId(){

        return $this->user_id;

    }


    public function getAccountType(){

        return $this->account_type;

    }

    public function getTransaction(){

        return $this->transaction;

    }


    public function getAmount(){

        return $this->amount;

    }


    public function getCurrency(){

        return $this->currency;

    }


}