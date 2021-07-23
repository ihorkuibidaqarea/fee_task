<?php

declare(strict_types=1);

namespace App\Service\Exchange;

use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Service\Math\MathAbstract;
use App\Entity\ExchangeResponse;
use \GuzzleHttp\Client;
use App\Exception\ExchangeException;
use App\Config\ConfigManager;


class ChangeMoney implements ChangeMoneyInterface
{
    private string $mainCurrency;
    private array $allowedCurrency;
    private array $exchangeRate;
    private MathAbstract $math;


    public function __construct(Client $client, MathAbstract $math)
    {
        $this->math = $math;
        $this->mainCurrency = ConfigManager::get('main_currency');
        $this->allowedCurrency = ConfigManager::get('allowed_currencies');
        // $response = $client->request('GET', 'http://api.exchangeratesapi.io/v1/latest?access_key=984d2f95a380439bdbb894a6f9521422');        
        // $responseCode = $response->getStatusCode(); 
        // $data = json_decode($response->getBody()->getContents(), true);
        
        // if (
        //     $responseCode === 200
        //     && isset($data['rates'])
        //     && is_array($data['rates']) 
        // ) {
            $this->exchangeRate = ["GBP"=> 0.882047, "JPY"=> 132.360679, "USD"=> 1.23396];
        // } else {
        //     throw new ExchangeException('Invalid respoce from Exchangeratesapi');
        // }            
    }

    
    private function getExchangeRate(string $currency)
    {  
        if (isset($this->exchangeRate[$currency])) {
            return (string) $this->exchangeRate[$currency];
        }
        throw new ExchangeException('Currency not found');
    }
    

    public function moneyExchange(string $amount, string $currency)
    {            
        if ($this->mainCurrency === $currency) {
            return new ExchangeResponse($amount);
        } 
        $this->isCurrencyAllowed($currency);
        $rate = $this->getExchangeRate($currency);
        $exchange =  $this->math->multiply($amount, $rate);                
        if ($this->math->compare($exchange, '0') > 0) {                        
            return new ExchangeResponse($exchange);
        }
    }


    public function reverseMoneyExchange(string $amount, string $currency)
    {            
        if ($this->mainCurrency === $currency) {
            return new ExchangeResponse($amount);
        }
        $this->isCurrencyAllowed($currency);
        $rate = $this->getExchangeRate($currency);
        $exchange = $this->math->divide($amount, $rate);                    
        if ($this->math->compare($exchange, '0') > 0) {                        
            return new ExchangeResponse($exchange);
        }
    }


    protected function isCurrencyAllowed(string $currency)
    {
        if (!in_array($currency, $this->allowedCurrency)) {
            throw new \Exception('Currency not allowed');
        } 
    }
}
