<?php

declare(strict_types=1);

namespace Src\Repository;

use Src\Repository\Interfaces\UserRepositoryAbstract;

class UserRepository extends UserRepositoryAbstract {

    public $transactions;

    public function getUserWithdravals( $user_id ){

        $user_key = 'users_'.$user_id;
        $user_transaction = $this->transactions[''.$user_key.''] ?? null;

        if( $user_transaction && is_array($user_transaction['withdrawals']) && !empty($user_transaction['withdrawals']) ){
           
            usort($user_transaction['withdrawals'], function ($element1, $element2) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($element2['date']);
                return $datetime2 - $datetime1;
            }); 
              
            return $user_transaction['withdrawals'];
        }
        return null;
        
    }

    public function getLastWeekUserWithdravals( $user_id ){

        $allWithdrawals = $this->getUserWithdravals($user_id);

        if( $allWithdrawals && is_array($allWithdrawals)  ){
            
            $weekAgo = date('Y-m-d', strtotime("-1 week"));
            
            
            $filteredWithdrawals = array_filter($allWithdrawals, function ($element1) use ($weekAgo) {
                $datetime1 = strtotime($element1['date']);
                $datetime2 = strtotime($weekAgo);

                if(($datetime1 - $datetime2) >= 0){
                    return true;
                }
                return false;
            });

            return $filteredWithdrawals;

        }

        return $allWithdrawals;
        
    }

    public function setUserWithdravals($user_id, $transaction_date, $amount, $currency){

        $user_key = 'users_'. $user_id;
        $user_transaction = $this->transactions[''.$user_key.''] ?? null;        

        if( $user_transaction && is_array($user_transaction['withdrawals']) ){
            
            array_push( $user_transaction['withdrawals'] , [ 'date' => $transaction_date, 'amount' => $amount , 'currency' => $currency ]);
            $this->transactions[''.$user_key.''] = $user_transaction;
            
        } else {
            $this->transactions[''.$user_key.''] = array( 'balance' => 10000, 'withdrawals' =>array(['date' => $transaction_date, 'amount' => $amount, 'currency' => $currency] ), 'deposits'=>array() );
        }
        
        return $this->transactions;
    }
    
}