<?php


use Psr\Container\ContainerInterface;

use Src\Service\UserFee\UserFeeAbstract;
use Src\Service\UserFee\UserFee;
use Src\Service\FileParser\FileParserAbstract;
use Src\Service\FileParser\CsvParser;
use Src\Repository\Interfaces\UserRepositoryAbstract;
use Src\Repository\UserRepository;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Service\Exchange\ChangeMoney;

use Src\Service\FeeCalculation\WithdrawTransaction;
use Src\Service\FeeCalculation\DepositTransaction;

use Src\Service\FeeCalculation\Interfaces\AccountTransactionAbstract;
use Src\Service\FeeCalculation\AccountTransaction;

use Src\Service\FeeCalculation\Interfaces\WithdrawTransactionAbstract;
use Src\Service\FeeCalculation\Interfaces\DepositTransactionAbstract;

use \GuzzleHttp\Client;

use function DI\create;
use function DI\get;


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
    WithdrawTransaction::class => create(WithdrawTransaction::class)->constructor(get(ChangeMoney::class), get(UserRepository::class)),
    DepositTransaction::class => create(DepositTransaction::class)->constructor(get(ChangeMoney::class), get(UserRepository::class)),
    // AccountTransactionAbstract::class => create(AccountTransaction::class)->constructor(),
    // UserFee::class => create()
    // ->constructor(get(FileParserAbstract::class), get(ChangeMoneyInterface::class), get(UserRepositoryAbstract::class)), 
    // DI\factory(function (ContainerInterface $c) {
    //     $fgh = $c;
    //     return new UserFee($c->get(CsvParser::class), $c->get(ChangeMoney::class), $c->get(UserRepository::class));
    // }),
    //  function (ContainerInterface $c) {
    //     $df = new UserFee($c->get(CsvParser::class), $c->get(ChangeMoney::class), $c->get(UserRepository::class));
    //    return $df;
    // },
    CsvParser::class => DI\factory(function() {
       return new CsvParser('transaction.csv');
    }),
    UserRepository::class => DI\factory(function() {
        return new UserRepository();
    }),
    ChangeMoney::class => DI\factory(function() {
        return new ChangeMoney(new Client());
    }),
    // 'BusinesWithdrawTransaction' => function (ContainerInterface $c) {
    //     return new Src\Service\FeeCalculation\BusinesWithdrawTransaction($c->get('ChangeMoney'), $c->get('UserRepository'));
    // },
    // 'PrivatWithdrawTransaction' => function (ContainerInterface $c) {
    //     return new Src\Service\FeeCalculation\PrivatWithdrawTransaction($c->get('ChangeMoney'), $c->get('UserRepository'));
    // },
    WithdrawTransactionAbstract::class => create(WithdrawTransaction::class)->constructor(get(ChangeMoney::class), get(UserRepository::class)),    
    DepositTransactionAbstract::class =>  create(DepositTransaction::class)->constructor(get(ChangeMoney::class), get(UserRepository::class)),
];