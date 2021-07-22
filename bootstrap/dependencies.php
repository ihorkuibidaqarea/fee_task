<?php


use Psr\Container\ContainerInterface;

use App\Service\UserFee\{UserFeeAbstract, UserFee};
use App\Service\FileParser\{FileParserAbstract, CsvParser};
use App\Repository\{Interfaces\UserRepositoryAbstract, UserRepository};
use App\Service\Exchange\{Interfaces\ChangeMoneyInterface, ChangeMoney};
use App\Service\FeeCalculation\{
    Interfaces\AccountTransactionAbstract,
    Interfaces\DepositTransactionAbstract,
    Interfaces\WithdrawTransactionAbstract,
    // BusinesWithdrawTransaction,
    // PrivatWithdrawTransaction,
    AccountTransaction,
    WithdrawTransaction,
    DepositTransaction
};
use App\Service\Math\Math;
use \GuzzleHttp\Client;
use function DI\create;
use function DI\get;
use App\Config\ConfigManager;


return [
    UserFeeAbstract::class => create(UserFee::class)->constructor(
        get(CsvParser::class),
        get(ChangeMoney::class),
        get(UserRepository::class),
        get(WithdrawTransactionAbstract::class),
        get(DepositTransactionAbstract::class)
    ),
    FileParserAbstract::class => create(CsvParser::class),
    UserRepositoryAbstract::class => create(UserRepository::class),
    ChangeMoneyInterface::class => create(ChangeMoney::class),
    WithdrawTransaction::class => create(WithdrawTransaction::class)->constructor(
        get(ChangeMoney::class),
        get(UserRepository::class),
    ),
    DepositTransaction::class => create(DepositTransaction::class)->constructor(
        get(ChangeMoney::class),
        get(UserRepository::class)
    ),
    CsvParser::class => DI\factory(function() {return new CsvParser('transaction.csv');}),
    UserRepository::class => DI\factory(function() {return new UserRepository();}),
    Math::class => create(Math::class)->constructor(ConfigManager::getConfig('culculate_scale')),
    ChangeMoney::class => create(ChangeMoney::class)->constructor(new Client(), get(Math::class)),
    WithdrawTransactionAbstract::class => create(WithdrawTransaction::class)->constructor(
        get(ChangeMoney::class),
        get(UserRepository::class),
        get(Math::class)
    ),    
    DepositTransactionAbstract::class => create(DepositTransaction::class)->constructor(
        get(ChangeMoney::class),
        get(UserRepository::class)
    ),
];