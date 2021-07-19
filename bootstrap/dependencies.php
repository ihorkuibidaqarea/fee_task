<?php

return [
    'UserFee' => function (Psr\Container\ContainerInterface $c) {
        return new Src\Service\UserFee\UserFee($c->get('CsvParser'), $c->get('ChangeMoney'), $c->get('UserRepository') );
    },
    'CsvParser' => DI\factory(function() {
        return new Src\Service\FileParser\CsvParser('transaction.csv');
    }),
    'UserRepository' => DI\factory(function() {
        return new Src\Repository\UserRepository();
    }),
    'ChangeMoney' => DI\factory(function() {
        return new Src\Service\Exchange\ChangeMoney();
    }),
    'BusinesWithdrawTransaction' => function (Psr\Container\ContainerInterface $c) {
        return new Src\Service\FeeCalculation\BusinesWithdrawTransaction( $c->get('ChangeMoney'), $c->get('UserRepository') );
    },
    'PrivatWithdrawTransaction' => function (Psr\Container\ContainerInterface $c) {
        return new Src\Service\FeeCalculation\PrivatWithdrawTransaction( $c->get('ChangeMoney'), $c->get('UserRepository') );
    },
    'WithdrawTransaction' => function (Psr\Container\ContainerInterface $c) {
        return new Src\Service\FeeCalculation\WithdrawTransaction( $c->get('ChangeMoney'), $c->get('UserRepository') );
    },    
    'DepositTransaction' => function (Psr\Container\ContainerInterface $c) {
        return new Src\Service\FeeCalculation\DepositTransaction( $c->get('ChangeMoney'), $c->get('UserRepository') );
    },
];