<?php

declare(strict_types=1);

namespace Src\Service\UserFee;

use Src\Service\FeeCalculation\AccountTransaction;
use Src\Service\FileParser\FileParserAbstract;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;

use Src\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;
use Src\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;
use Src\Service\Math\Math;


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
        $fee = [];
        foreach ($this->data as $operation) {            
            $fee[] = $this->calculateFee($operation);
        }
        return $fee;       
    }

    
    private function calculateFee($operation)
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

            $amountFee = $this->roundFee($fee);
            return $amountFee;
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
        $amount = (string) number_format($amount, 2, ',', ' ');
        return $amount;
    }
}
