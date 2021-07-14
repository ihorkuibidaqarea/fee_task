<?php
require __DIR__.'/../vendor/autoload.php';


use Src\Service\FeeCalculation\AccountTransaction;
use Src\Service\UserFee\UserFee;
use Src\Service\FileParser\CsvParser;
use Src\Service\Http\HttpRequest;
use \GuzzleHttp\Client;
use Src\Service\Exchange\ChangeMoney;
use Src\Repository\UserRepository;



try{   
               
    $fe = (new UserFee( new CsvParser('transaction.csv'),  new ChangeMoney(), new UserRepository() ))->getFee();

    print_r( $fe );
    

} catch (\Exception $e) {

    echo $e->getMessage();

} 


