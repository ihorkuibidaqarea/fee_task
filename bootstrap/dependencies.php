<?php


use Psr\Container\ContainerInterface;

use App\Service\UserFee\UserFeeAbstract;
use App\Service\UserFee\UserFee;
use App\Service\FileParser\FileParserAbstract;
use App\Service\FileParser\CsvParser;
use App\Repository\Interfaces\UserRepositoryAbstract;
use App\Repository\UserRepository;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Service\Exchange\ChangeMoney;

use App\Service\FeeCalculation\WithdrawTransaction;
use App\Service\FeeCalculation\DepositTransaction;

use App\Service\FeeCalculation\Interfaces\AccountTransactionAbstract;
use App\Service\FeeCalculation\AccountTransaction;

use App\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;
use App\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;

use \GuzzleHttp\Client;

use function DI\create;
use function DI\get;


return [
    UserFeeAbstract::class => create(UserFee::class)
                              ->constructor(
                                    get(CsvParser::class),
                                    get(ChangeMoney::class),
                                    get(UserRepository::class),
                                    get(WithdrawTransactionAbstract::class),
                                    get(DepositTransactionAbstract::class)
                              ),
    FileParserAbstract::class => create(CsvParser::class),
    UserRepositoryAbstract::class => create(UserRepository::class),
    ChangeMoneyInterface::class => create(ChangeMoney::class),
    WithdrawTransaction::class => create(WithdrawTransaction::class)
                                  ->constructor(
                                      get(ChangeMoney::class),
                                      get(UserRepository::class)
                                  ),
    DepositTransaction::class => create(DepositTransaction::class)
                                 ->constructor(
                                     get(ChangeMoney::class),
                                     get(UserRepository::class)
                                 ),
    CsvParser::class => DI\factory(function() {
       return new CsvParser('transaction.csv');
    }),
    UserRepository::class => DI\factory(function() {
        return new UserRepository();
    }),
    ChangeMoney::class => DI\factory(function() {
        return new ChangeMoney(new Client());
    }),
    WithdrawTransactionAbstract::class => create(WithdrawTransaction::class)
                                        ->constructor(
                                            get(ChangeMoney::class),
                                            get(UserRepository::class)
                                        ),    
    DepositTransactionAbstract::class => create(DepositTransaction::class)
                                         ->constructor(
                                            get(ChangeMoney::class),
                                            get(UserRepository::class)
                                         ),
];