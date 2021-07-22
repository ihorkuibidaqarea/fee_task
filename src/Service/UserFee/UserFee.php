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


class UserFee extends UserFeeAbstract
{
    private $data;
    private ChangeMoneyInterface $exchange;
    private UserRepositoryAbstract $repository;
    private WithdrawTransactionAbstract $withdraw;
    private DepositTransactionAbstract $deposit;
    private MathAbstract $math;
   

    public function __construct(
        FileParserAbstract $parser,
        ChangeMoneyInterface $exchange,
        UserRepositoryAbstract $repository,
        WithdrawTransactionAbstract $withdraw,
        DepositTransactionAbstract $deposit,
        MathAbstract $math
    ) {
        $this->data = $parser->data();
        $this->exchange = $exchange;
        $this->repository = $repository;
        $this->withdraw = $withdraw;
        $this->deposit = $deposit;
        $this->math = $math;
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
                $operation->getAccountType(),
                $this->exchange,
                $this->repository,
                $this->math
            ))
            ->getTransaction()
            ->fee( 
                $operation->getDate(), 
                $operation->getUserId(), 
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
