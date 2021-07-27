<?php

declare(strict_types=1);

namespace Test\Service;

use App\Service\FileParser\CsvParser;
use App\Service\Exchange\ChangeMoney;
use App\Repository\UserRepository;
use App\Service\UserFee\UserFee;
use App\Service\FeeCalculation\{
    WithdrawTransaction,
    DepositTransaction
};
use PHPUnit\Framework\TestCase;
use App\Entity\Operaiton;
use \GuzzleHttp\Client;
use App\Service\Math\Math;

class FeeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();       
    }
    
    /**
     * @test
     *
     * @param array $data
     * @param array $expectation
     *
     * @dataProvider dataProviderForFeeTesting
     */

    public function testFee(array $data, array $expectation)
    {
        $CsvParserMock = \Mockery::mock('App\Service\FileParser\CsvParser', 'App\Service\FileParser\FileParserAbstract');
        $CsvParserMock->shouldReceive('data')->andReturn($data);
        $math = new Math(5);
        $changeMoney = new ChangeMoney(new Client(), $math);
        $userRepository = new UserRepository();
        
        $userFeeResponce = new UserFee(
            $CsvParserMock,
            $changeMoney,
            $userRepository,
            $math
        );      
        $userFee = $userFeeResponce->getFee();
        $this->assertEquals($userFee, $expectation);
    }


    public function dataProviderForFeeTesting(): array
    {
        $fiveDaysAgo = date('Y-m-d', strtotime('-5 days'));
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
        $oneDayAgo = date('Y-m-d', strtotime('-1 days'));

        return [
                [
                    [
                        new Operaiton ($fiveDaysAgo, 1, 'private', 'withdraw', '1200.00', 'EUR'),
                        new Operaiton ($fiveDaysAgo, 1, 'private', 'withdraw', '1000.00', 'EUR'),
                        new Operaiton ($threeDaysAgo, 1, 'private', 'withdraw', '1000.00', 'EUR'),
                        new Operaiton ($oneDayAgo, 1, 'private', 'deposit', '200.00', 'EUR'),
                        new Operaiton ($oneDayAgo, 2, 'business', 'withdraw', '900.00', 'EUR')

                    ],
                    ["0,60", "3,00", "3,00", "0,06", "0,00"]
                ],               
        ];
    }
}
