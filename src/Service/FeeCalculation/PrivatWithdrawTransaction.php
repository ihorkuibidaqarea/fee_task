<?php

declare(strict_types=1);

namespace Src\Service\FeeCalculation;

use Src\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use Src\Service\Math\Math;
use Src\Repository\UserRepository;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


class PrivatWithdrawTransaction implements FeeCalculationInterface {

    private const FEE  = '0.003';
    private const SCALE = 5;
    private const ALLOWED_AMOUNT = '1000';
    public $math;
    private $exchange;
    private $repository;

    public function __construct( ChangeMoneyInterface $exchange,  UserRepositoryAbstract $repository ){

        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = new Math( self::SCALE );

    }
    


    public function fee( string $operation_date,  $user_id, string $user_type, string $amount, string $currency ){
         
        $value = $this->getAmmountForFee( $operation_date, $user_id, $amount, $currency);

        $fee =  $this->math->multiply( (string) $value, self::FEE );

        return  $fee;

    }



    private function getAmmountForFee($operation_date, $user_id, $amount, $currency){

        $userWithdrawals = $this->repository->getLastWeekWithdravals( $user_id );
        
        if( is_array( $userWithdrawals ) ){
                                    
            if( count( $userWithdrawals ) > 2  ){
                
                return $amount;

            } else {

               $feeDiffernce = $this->calculateFeeDiffernce( $userWithdrawals, $amount, $currency );
            
               return $feeDiffernce;
                               
            }

        }  

        $allowedAmountInCurrency = $this->exchange->moneyExchange( self::ALLOWED_AMOUNT, $currency);

        if( $allowedAmountInCurrency['success'] ){

            $FirstRequestAllowedAmount = $this->math->subtract( $amount, $allowedAmountInCurrency['amount'] );
                        
            if( $this->math->compare( (string) $FirstRequestAllowedAmount, '0') > 0 ){
                
                return $FirstRequestAllowedAmount; 

            } 
            
            return '0';  
        }        
        
        throw new \Exception('Fee Amount error');         

    }

    


    private function calculateWithdrawedAmountInEuro( $userWithdrawals ){

        $withdrawed = '0';

        foreach( $userWithdrawals as $key){

            $withdravedInEuro = $this->exchange->moneyExchange($key['amount'], $key['currency']);

            if($withdravedInEuro['success']){

                $withdrawed = $this->math->add( (string) $withdrawed,  (string) $withdravedInEuro['amount'] );

            } else {
                
                throw new \Exception('Exchange error');
            }
            
        }

        return $withdrawed;
    }





    private function calculateFeeDiffernce( $userWithdrawals, $amount, $currency ){

        
        $withdrawed = $this->calculateWithdrawedAmountInEuro( $userWithdrawals );         
        $difference =  $this->math->subtract((string) self::ALLOWED_AMOUNT, (string) $withdrawed );

        if(  $this->math->compare( $difference, '0') <= 0  ){

            return $amount;

        } else {

           $allowedInCurrency =  $this->exchange->moneyExchange( $difference, $currency );           
           
           if($allowedInCurrency['success']){

                $forFee =  $this->math->subtract((string) $amount, (string) $allowedInCurrency['amount'] );

                if(  $this->math->compare( $forFee, '0') <= 0  ){

                    return '0';
        
                } else {

                    return $forFee;

                }
                
            } else {

                throw new \Exception('Exchange error'); 

            } 
            
        }
    }
   

    
}