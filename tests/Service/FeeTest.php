<?php

declare(strict_types=1);

namespace Test\Service;

use Src\Service\FileParser\CsvParser;
use Src\Service\Exchange\ChangeMoney;
use Src\Repository\UserRepository;
use Src\Service\UserFee\UserFee;
use Src\Service\UserFee\UserFeeAbstract;
use PHPUnit\Framework\TestCase;

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

    public function testFee( array $data, array $expectation)
    {

        $CsvParserMock = \Mockery::mock(  'Src\Service\FileParser\CsvParser', 'Src\Service\FileParser\FileParserAbstract' );
        $CsvParserMock->shouldReceive('data')->andReturn( $data );
        
        $responce = (new UserFee( $CsvParserMock,  new ChangeMoney(), new UserRepository() ))->getFee();
        $this->assertEquals($responce, $expectation);

    }



    public function dataProviderForFeeTesting(): array
    {
        $fiveDaysAgo = date('Y-m-d', strtotime('-5 days'));
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
        $oneDayAgo = date('Y-m-d', strtotime('-1 days'));

        return [
            [
                [
                    (object) ['date' => "$fiveDaysAgo",'user_id' => 1, 'account_type' => 'private', 'transaction' => 'withdraw', 'amount' => '1200.00', 'currency' => 'EUR'],
                    (object) ['date' => "$fiveDaysAgo", 'user_id' => 1, 'account_type' => 'private', 'transaction' => 'withdraw', 'amount' => '1000.00', 'currency' => 'EUR'],
                    (object) ['date' => "$threeDaysAgo", 'user_id' => 1, 'account_type' =>'private', 'transaction' => 'withdraw', 'amount' => '1000.00', 'currency' => 'EUR'],
                    (object) ['date' => "$oneDayAgo", 'user_id' => 1, 'account_type' => 'private', 'transaction' => 'deposit', 'amount' => '200.00', 'currency' => 'EUR'],
                    (object) ['date' => "$oneDayAgo", 'user_id' => 2, 'account_type' => 'business', 'transaction' => 'withdraw', 'amount' => '900.00', 'currency' => 'EUR']
                ],
                ["EUR - 0,60", "EUR - 3,00", "EUR - 3,00", "EUR - 0,06", "EUR - 0,00"]
            ]
        ];
    }
    
    
}
