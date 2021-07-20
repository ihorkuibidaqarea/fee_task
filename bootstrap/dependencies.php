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


return [
    UserFeeAbstract::class => DI\create(UserFee::class),
    FileParserAbstract::class => DI\create(CsvParser::class),
    UserRepositoryAbstract::class => DI\create(UserRepository::class),
    UserFee::class => function (ContainerInterface $c) {
        $df = new UserFee($c->get(CsvParser::class), $c->get(ChangeMoney::class), $c->get(UserRepository::class));
       return $df;
    },
    CsvParser::class => DI\factory(function() {
       return new CsvParser('transaction.csv');
    }),
    UserRepository::class => DI\factory(function() {
        return new UserRepository();
    }),
    ChangeMoney::class => DI\factory(function() {
        return new ChangeMoney();
    }),
    'BusinesWithdrawTransaction' => function (ContainerInterface $c) {
        return new Src\Service\FeeCalculation\BusinesWithdrawTransaction($c->get('ChangeMoney'), $c->get('UserRepository'));
    },
    'PrivatWithdrawTransaction' => function (ContainerInterface $c) {
        return new Src\Service\FeeCalculation\PrivatWithdrawTransaction($c->get('ChangeMoney'), $c->get('UserRepository'));
    },
    'WithdrawTransaction' => function (ContainerInterface $c) {
        return new Src\Service\FeeCalculation\WithdrawTransaction($c->get('ChangeMoney'), $c->get('UserRepository'));
    },    
    'DepositTransaction' => function (ContainerInterface $c) {
        return new Src\Service\FeeCalculation\DepositTransaction( $c->get('ChangeMoney'), $c->get('UserRepository') );
    },
];