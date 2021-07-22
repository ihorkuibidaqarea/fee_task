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
use App\Service\Math\Math;


class UserFee extends UserFeeAbstract
{
    private $data;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;
    private WithdrawTransactionAbstract $withdraw;
    private DepositTransactionAbstract $deposit;
    private Math $math;
    private const SCALE = 2;
   

    public function __construct(
                        FileParserAbstract $parser,
                        ChangeMoneyInterface $exchange,
                        UserRepositoryAbstract $repository,
                        WithdrawTransactionAbstract $withdraw,
                        DepositTransactionAbstract $deposit
                    )
    {
        $this->data = $parser->data();
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->withdraw = $withdraw;
        $this->deposit = $deposit;
        $this->math = new Math(self::SCALE);
    }


    public function getFee()
    {        
        foreach ($this->data as $operation) {            
            echo $this->calculateFee($operation).' ';
        }      
    }

    
    private function calculateFee(Operaiton $operation)
    {
        try {
            $fee = (new AccountTransaction(
                $operation->getOperationName(),
                $this->exchange,
                $this->repository,
                $this->withdraw,
                $this->deposit
            ))
            ->getTransaction()
            ->fee( 
                $operation->getDate(), 
                $operation->getUserId(), 
                $operation->getAccountType(), 
                $operation->getAmount(), 
                $operation->getCurrency()
            );    

            $this->repository->setUserWithdravals(
                $operation->getUserId(), 
                $operation->getDate(), 
                $operation->getAmount(), 
                $operation->getCurrency()
            );

            return $this->roundFee($fee);
        } catch (\Exception $e) {
            return $e->getMessage(); 
        }
    }
    
    private function roundFee(string $fee): string
    {
        $amount = (float) $this->math->divide(
            (string) ceil($this->math->multiply($fee, '100')),
            '100'
        );
        return (string) number_format($amount, 2, ',', ' ');
    }
}
