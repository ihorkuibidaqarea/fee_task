<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Repository\UserRepository;
use App\Service\Math\Math;
use App\Config\ConfigManager;


class BusinesWithdrawTransaction implements FeeCalculationInterface
{
    private $feePercent;
    private $allowedAmount; // const ALLOWED_AMOUNT = '1000';
    private Math $math;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;


    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository, Math $math)
    {
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = $math;
        $this->feePercent = ConfigManager::getConfig('busines_withdraw_fee');
        $this->allowedAmount = ConfigManager::getConfig('week_allowed_withdraw_amount');
    }
    

    public function fee(string $operation_date, $user_id, string $user_type, string $amount, string $currency)
    {          
        $value = $this->getAmmountForFee($operation_date, $user_id, $amount, $currency);
        $fee = $this->math->multiply((string) $value, $this->feePercent);
        return $fee; 
    }


    private function getAmmountForFee($operation_date, $user_id, $amount, $currency)
    {
        $userWithdrawals = $this->repository->getLastWeekWithdravals($user_id);
        if (is_array($userWithdrawals)) {                                    
            if (count( $userWithdrawals ) > 2) {
                return $amount;
            } else {
               $amountForFee = $this->feeDiffernce($userWithdrawals, $amount, $currency);
               return $amountForFee;                               
            }
        }  

        $allowedAmountInCurrency = $this->exchange->moneyExchange($this->allowedAmount, $currency);
        if ($allowedAmountInCurrency->success) {
            $FirstRequestAllowedAmount = $this->math->subtract($amount, $allowedAmountInCurrency->amount);
            if ($this->math->compare((string) $FirstRequestAllowedAmount, '0') > 0) {
                return $FirstRequestAllowedAmount; 
            } 
            return '0';  
        }
        throw new \Exception('Fee Amount error');
    }


    private function wthdrawedAmountInEuro($userWithdrawals)
    {
        $withdrawed = '0';
        foreach ($userWithdrawals as $withdrawal) {
            $withdravedInEuro = $this->exchange->moneyExchange($withdrawal->amount, $withdrawal->currency);

            if ($withdravedInEuro->success) {
                $withdrawed = $this->math->add((string) $withdrawed, (string) $withdravedInEuro->amount);
            } else {
                throw new \Exception('Exchange error');
            }            
        }
        return $withdrawed;
    }


    private function feeDiffernce($userWithdrawals, $amount, $currency)
    {        
        $withdrawed = $this->wthdrawedAmountInEuro($userWithdrawals);
        $difference =  $this->math->subtract($this->allowedAmount, (string) $withdrawed);
        if ($this->math->compare($difference, '0') <= 0) {
            return $amount;
        } else {
           $allowedInCurrency = $this->exchange->moneyExchange($difference, $currency);           
           
           if ($allowedInCurrency->success) {
                $forFee =  $this->math->subtract((string) $amount, (string) $allowedInCurrency->amount);

                if ($this->math->compare($forFee, '0') <= 0) {
                    return '0';        
                } 

                return $forFee;                
            } else {
                throw new \Exception('Exchange error'); 
            }
        }
    }
}
