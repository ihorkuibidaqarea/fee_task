<?php

declare(strict_types=1);

namespace App\Service\Exchange;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Service\Math\Math;
use App\Entity\ExchangeResponse;
use \GuzzleHttp\Client;


class ChangeMoney implements ChangeMoneyInterface
{
    private string $mainCurrency;
    private array $exchangeRate;
    private Math $math;


    public function __construct(Client $client, Math $math)
    {
        $this->math = $math;
        $this->mainCurrency = 'EUR';
        $response = $client->request('GET', 'http://api.exchangeratesapi.io/v1/latest?access_key=984d2f95a380439bdbb894a6f9521422');        
        $responseCode = $response->getStatusCode(); 
        $data = json_decode($response->getBody()->getContents(), true);
        
        if (
            $responseCode === 200
            && isset($data['rates'])
            && is_array($data['rates']) 
        ) {
            $this->exchangeRate = $data['rates'];
        } else {
            throw new \Exception('Invalid respoce from Exchangeratesapi');
        }            
    }

    
    private function getExchangeRate(string $currency)
    {
        if (isset($this->exchangeRate[$currency])) {
            return $this->exchangeRate[$currency];
        }
        throw new \Exception('Currency not found');
    }
    

    public function moneyExchange(string $amount, string $currency)
    {            
        if ($this->mainCurrency === $currency) {
            return new ExchangeResponse($amount);
        } else {
            $rate = $this->getExchangeRate($currency);
            $exchange =  $this->math->multiply($amount, (string) $rate);                
            if ($this->math->compare($exchange, '0') > 0) {                        
                return new ExchangeResponse($exchange);
            }                          
        }
    }


    public function reverseMoneyExchange(string $amount, string $currency)
    {            
        if ($this->mainCurrency === $currency) {
            return new ExchangeResponse($amount);
        } else {
            $rate = $this->getExchangeRate($currency);
            $exchange = $this->math->divide($amount, (string) $rate);                    
            if ($this->math->compare($exchange, '0') > 0) {                        
                return new ExchangeResponse($exchange);
            }                        
        }
    }
}
