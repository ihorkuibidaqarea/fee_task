<?php

declare(strict_types=1);

namespace App\Service\FeeCalculation;

use App\Service\FeeCalculation\Interfaces\FeeCalculationInterface;
use App\Service\Math\MathAbstract;
use App\Repository\UserRepository;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Config\ConfigManager;
use App\Exception\ExchangeException;


class PrivatWithdrawTransaction implements FeeCalculationInterface
{
    private $feePercent;
    private $allowedAmount;
    private $freeWeekWithdrawals;
    public MathAbstract $math;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;


    public function __construct(ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository, MathAbstract $math)
    {
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = $math;
        $this->feePercent = ConfigManager::get('privat_withdraw_fee');
        $this->allowedAmount = ConfigManager::get('week_allowed_withdraw_amount');
        $this->freeWeekWithdrawals = ConfigManager::get('week_allowed_withdraw_atemps');
    }    


    public function fee(string $operationDate, int $userId, string $amount, string $currency): string
    {          
        $value = $this->getAmmountForFee($operationDate, $userId, $amount, $currency);
        return $this->math->multiply($value, $this->feePercent);
    }


    private function getAmmountForFee(string $operationDate, int $userId, string $amount, string $currency): string
    {
        $userWithdrawals = $this->repository->getLastWeekWithdravals($userId);
        if (is_array($userWithdrawals)) {
            if (count($userWithdrawals) > $this->freeWeekWithdrawals) {
                return $amount;
            } 
            $amountForFee = $this->feeDiffernce($userWithdrawals, $amount, $currency);
            return $amountForFee;             
        }  

        $allowedAmountInCurrency = $this->exchange->moneyExchange($this->allowedAmount, $currency);
        if ($allowedAmountInCurrency->success) {
            $FirstRequestAllowedAmount = $this->math->subtract($amount, $allowedAmountInCurrency->amount);
            if ($this->math->compare($FirstRequestAllowedAmount, '0') > 0) {
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
                throw new ExchangeException('Exchange error');
            }            
        }
        return $withdrawed;
    }


    private function feeDiffernce(array $userWithdrawals, string $amount, string $currency): string
    {        
        $withdrawed = $this->wthdrawedAmountInEuro($userWithdrawals);
        $difference =  $this->math->subtract($this->allowedAmount, $withdrawed);
        if ($this->math->compare( $difference, '0') <= 0) {
            return $amount;
        }

        $allowedInCurrency = $this->exchange->moneyExchange($difference, $currency);
        if ($allowedInCurrency->success) {
            $forFee =  $this->math->subtract($amount, $allowedInCurrency->amount);
            if ($this->math->compare($forFee, '0') <= 0) {
                return '0';        
            } 
            return $forFee;            
        } 
        throw new ExchangeException('Exchange error');
    }
}
