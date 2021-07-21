<?php

declare(strict_types=1);

namespace Src\Service\Exchange;

use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Service\Math\Math;
use Src\Entity\ExchangeResponse;


class ChangeMoney implements ChangeMoneyInterface
{
    private const MAIN_CURRENCY = 'EUR';
    private const SCALE = 5;
    private $exchangeRate;
    private $math;


    public function __construct()
    {
        $this->math = new Math(self::SCALE);
        $client = new \GuzzleHttp\Client();
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
        if (SELF::MAIN_CURRENCY === $currency) {
            return new ExchangeResponse($amount);
        } else {
            $rate = $this->getExchangeRate((string) $currency);
            $exchange =  $this->math->multiply((string) $amount, (string) $rate);                
            if ($this->math->compare($exchange, '0') > 0) {                        
                return new ExchangeResponse($exchange);
            }
                          
        }
    }


    public function reverseMoneyExchange(string $amount, string $currency)
    {            
        if (SELF::MAIN_CURRENCY === $currency) {
            return new ExchangeResponse($amount);
        } else {
            $rate = $this->getExchangeRate((string) $currency);
            $exchange = $this->math->divide((string) $amount, (string) $rate);                    
            if ($this->math->compare($exchange, '0') > 0) {                        
                return new ExchangeResponse($exchange);
            }                        
        }
    }
}
