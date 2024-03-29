<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Repository\UserRepository;
use App\Service\Math\MathAbstract;
use App\Config\ConfigManager;


class BusinesWithdrawTransaction implements FeeCalculationInterface
{
    private string $feePercent;
    private string $weekAllowedWithdrawAmount;
    private int $weekAllowedWithdrawAtemps;
    private MathAbstract $math;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;


    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository, MathAbstract $math)
    {
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = $math;
        $this->feePercent = ConfigManager::get('busines_withdraw_fee');
        $this->weekAllowedWithdrawAmount = ConfigManager::get('week_allowed_withdraw_amount'); //allowedAmount
        $this->weekAllowedWithdrawAtemps = ConfigManager::get('week_allowed_withdraw_atemps'); //freeWeekWithdrawals
    }
    

    public function fee(string $operationDate, int $userId, string $amount, string $currency): string
    {          
        $value = $this->getAmmountForFee($operationDate, $userId, $amount, $currency);
        return $this->math->multiply((string) $value, $this->feePercent);
    }


    private function getAmmountForFee(string $operationDate, int $userId, string $amount, string $currency): string
    {
        $userWithdrawals = $this->repository->getLastWeekWithdravals($userId);
        if (is_array($userWithdrawals)) {
            if (count( $userWithdrawals ) > $this->weekAllowedWithdrawAtemps) {
                return $amount;
            }
            $amountForFee = $this->feeDiffernce($userWithdrawals, $amount, $currency);
            return $amountForFee; 
        }  

        $allowedAmountInCurrency = $this->exchange->moneyExchange($this->weekAllowedWithdrawAmount, $currency);
        if ($allowedAmountInCurrency->success) {
            $FirstRequestAllowedAmount = $this->math->subtract($amount, $allowedAmountInCurrency->amount);
            if ($this->math->compare((string) $FirstRequestAllowedAmount, '0') > 0) {
                return $FirstRequestAllowedAmount; 
            } 
            return '0';  
        }
        throw new \Exception('Fee Amount error');
    }


    private function wthdrawedAmountInEuro(array $userWithdrawals): string
    {
        $withdrawed = '0';
        foreach ($userWithdrawals as $withdrawal) {
            $withdravedInEuro = $this->exchange->moneyExchange($withdrawal->amount, $withdrawal->currency);
            if ($withdravedInEuro->success) {
                $withdrawed = $this->math->add($withdrawed, $withdravedInEuro->amount);
            } else {
                throw new \Exception('Exchange error');
            }            
        }
        return $withdrawed;
    }


    private function feeDiffernce(array $userWithdrawals, string $amount, string $currency): string
    {        
        $withdrawed = $this->wthdrawedAmountInEuro($userWithdrawals);
        $difference =  $this->math->subtract($this->weekAllowedWithdrawAmount, $withdrawed);
        if ($this->math->compare($difference, '0') <= 0) {
            return $amount;
        }

        $allowedInCurrency = $this->exchange->moneyExchange($difference, $currency); 
        if ($allowedInCurrency->success) {
            $forFee =  $this->math->subtract((string) $amount, (string) $allowedInCurrency->amount);
            if ($this->math->compare($forFee, '0') <= 0) {
                return '0';        
            } 
            return $forFee;                
        }
        throw new \Exception('Exchange error');        
    }
}
