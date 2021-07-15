<?php

declare(strict_types=1);

namespace Src\Service\UserFee;

use Src\Service\FeeCalculation\AccountTransaction;
use Src\Service\FileParser\FileParserAbstract;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;

class UserFee extends UserFeeAbstract {

    private $data;
    private $exchange;
    private $repository;
   

    public function __construct( FileParserAbstract $parser, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository ){

        $this->data = $parser->data();
        $this->exchange = $exchange;
        $this->repository = $repository; 
        
    }

    public function getFee(){
        
        $fees_array = [];

        foreach( $this->data as $transaction){
            
            $fees_array[] = $this->calculateFee($transaction);

        }
       
        return $fees_array;

    }

    
    private function calculateFee( $data ){

        try {

            $fee = (new AccountTransaction($data->transaction, $this->exchange, $this->repository))->transaction->fee( $data->date, $data->user_id, $data->account_type, $data->amount, $data->currency);
            
            $this->repository->setUserWithdravals($data->user_id, $data->date, $data->amount, $data->currency);

            $fee = ceil(($fee * 100))/100;

            $money = number_format($fee, 2, ',', ' ');
           
            return $data->currency .' - '. $money;

        } catch (\Exception $e) {

            return $e->getMessage();
 
         } 
       
    }

    
}