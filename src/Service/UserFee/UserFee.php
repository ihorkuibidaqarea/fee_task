<?php

declare(strict_types=1);

namespace Src\Service\UserFee;

use Src\Service\FeeCalculation\AccountTransaction;
use Src\Service\FileParser\FileParserAbstract;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;
use Src\Service\Math\Math;


class UserFee extends UserFeeAbstract
{
    private $data;
    private $exchange;
    private $repository;
    // private $trasaction;
    private $math;
    private const SCALE = 2;
   
// FileParserAbstract $parser, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository
    public function __construct(
                        FileParserAbstract $parser,
                        ChangeMoneyInterface $exchange,
                        UserRepositoryAbstract $repository
                        // AccountTransaction $trasaction
                    )
    {
        $this->data = $parser->data();
        $this->exchange = $exchange;
        $this->repository = $repository;
        // $this->trasaction = $trasaction; 
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
            $fee = (new AccountTransaction($operation->getOperationName(), $this->exchange, $this->repository))
                        ->getTransaction()
                        ->fee( 
                            $operation->getDate(), 
                            $operation->getUserId(), 
                            $operation->getAccountType(), 
                            $operation->getAmount(), 
                            $operation->getCurrency()
                        );    
                        $fg = 78;        
            $this->repository->setUserWithdravals(
                                                $operation->getUserId(), 
                                                $operation->getDate(), 
                                                $operation->getAmount(), 
                                                $operation->getCurrency()
                                );
            $fee = (float) $this->math->divide(
                                       (string) ceil($this->math->multiply((string) $fee, '100')),
                                        '100'
                                    );
            $money = number_format($fee, 2, ',', ' ');           
            return $operation->getCurrency() .' - '. $money;
        } catch (\Exception $e) {
            return $e->getMessage(); 
         }
    }    
}
