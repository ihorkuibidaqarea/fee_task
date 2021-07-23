<?php


use Psr\Container\ContainerInterface;

use App\Service\UserFee\{UserFeeAbstract, UserFee};
use App\Service\FileParser\{FileParserAbstract, CsvParser};
use App\Repository\{Interfaces\UserRepositoryAbstract, UserRepository};
use App\Service\Exchange\{Interfaces\ChangeMoneyInterface, ChangeMoney};
use App\Service\Math\MathAbstract;
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
        get(MathAbstract::class)
    ),

    FileParserAbstract::class => create(CsvParser::class),

    UserRepositoryAbstract::class => create(UserRepository::class),

    ChangeMoneyInterface::class => create(ChangeMoney::class),    

    CsvParser::class => DI\factory(function() {return new CsvParser('transaction.csv');}),

    UserRepository::class => DI\factory(function() {return new UserRepository();}),

    MathAbstract::class => create(Math::class)->constructor(ConfigManager::get('culculate_scale')),

    Math::class => create(Math::class)->constructor(ConfigManager::get('culculate_scale')),

    ChangeMoney::class => create(ChangeMoney::class)->constructor(new Client(), get(Math::class)),    
];