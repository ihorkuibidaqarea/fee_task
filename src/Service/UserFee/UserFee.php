<?php

declare(strict_types=1);

namespace App\Service\UserFee;

use App\Service\FileParser\FileParserAbstract;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Service\FeeCalculation\{
    Interfaces\WithdrawTransactionAbstract,
    Interfaces\DepositTransactionAbstract,
    AccountTransaction
};
use App\Entity\Operaiton;
use App\Service\Math\MathAbstract;
use App\Config\ConfigManager;
use App\Exception\ExchangeException;


class UserFee extends UserFeeAbstract
{
    private $data;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;
    private MathAbstract $math;
    private array $allowedCurrency;
   

    public function __construct(
        FileParserAbstract $parser,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        MathAbstract $math
    ) {
        $this->data = $parser->data();
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->math = $math;
        $this->allowedCurrency = ConfigManager::getConfig('allowed_currencies');
    }


    public function getFee()
    {        
        foreach ($this->data as $operation) {            
            echo $operation->getCurrency() ."   ". $this->calculateFee($operation).' ';
        }      
    }

    
    private function calculateFee(Operaiton $operation)
    {          
        try {
            if (!in_array($operation->getCurrency(), $this->allowedCurrency)) {
                throw new \Exception('Currency not allowed');
            }  
            $fee = (new AccountTransaction(
                $operation->getOperationName(),
                $operation->getAccountType(),
                $this->exchange,
                $this->repository,
                $this->math
            ))
            ->getTransaction()
            ->fee($operation->getDate(), $operation->getUserId(), $operation->getAmount(),$operation->getCurrency());

            $this->repository->setUserWithdravals(
                $operation->getUserId(), 
                $operation->getDate(), 
                $operation->getAmount(), 
                $operation->getCurrency()
            );

            return $this->roundFee($fee);

        } catch (ExchangeException $e) {
            return $e->getMessage(); 
        } catch(\Exception $e) {
            return $e->getMessage(); 
        }
    }
    
    private function roundFee(string $fee): string
    {
        if ($this->math->compare($fee,'0') < 0) {
            throw new \Exception('Fee Round error');
        }
        $amountInCents = (string) ceil($this->math->multiply($fee, '100'));
        $amount = (float) $this->math->divide($amountInCents, '100');
        return (string) number_format($amount, 2, ',', ' ');;
    }
}
