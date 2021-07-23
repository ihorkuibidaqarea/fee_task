<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Entity\Transaction;

class UserRepository extends UserRepositoryAbstract
{
    public $transactions;


    public function getWithdravals(int $userId)
    {
        $userKey = 'users_'.$userId;
        $userTransaction = $this->transactions[$userKey] ?? null;

        if ( 
            $userTransaction 
            && is_array($userTransaction['withdrawals']) 
            && !empty($userTransaction['withdrawals']) 
        ) {           
            usort($userTransaction['withdrawals'], function ($element1, $element2) {
                $datetime1 = strtotime($element1->date);
                $datetime2 = strtotime($element2->date);
                return $datetime2 - $datetime1;
            });
            return $userTransaction['withdrawals'];
        }

        return null;        
    }


    public function getLastWeekWithdravals(int $userId)
    {
        $allWithdrawals = $this->getWithdravals($userId);

        if ($allWithdrawals && is_array($allWithdrawals)) {            
            $weekAgo = date('Y-m-d', strtotime("-1 week"));

            $filteredWithdrawals = array_filter($allWithdrawals, function ($element1) use ($weekAgo) {
                $datetime1 = strtotime($element1->date);
                $datetime2 = strtotime($weekAgo);
                if (($datetime1 - $datetime2) >= 0){
                    return true;
                }
                return false;
            });

            return $filteredWithdrawals;
        }
        return $allWithdrawals;        
    }


    public function setUserWithdravals(int $userId, string $transactionDate, string $amount, string $currency): void
    {
        $userKey = 'users_'. $userId;
        $userTransaction = $this->transactions[$userKey] ?? null;

        if ($userTransaction && is_array($userTransaction['withdrawals'])) {            
            array_push( $userTransaction['withdrawals'] , new Transaction($transactionDate, $amount, $currency));
            $this->transactions[$userKey] = $userTransaction;            
        } else {
            $this->transactions[$userKey] = array( 
                'balance' => 10000,
                'withdrawals' =>array(new Transaction($transactionDate, $amount, $currency)),
                'deposits'=>array()
            );
        }        
    }
}
