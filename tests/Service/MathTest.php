<?php

declare(strict_types=1);

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use App\Service\Math\Math;

class MathTest extends TestCase
{
    /**
     * @var Math
     */
    private $math;

    public function setUp(): void
    {
        parent::setUp();
        $this->math = new Math(2);        
    }

    /**
     * @test
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals($expectation,  $this->math->add($leftOperand, $rightOperand) );
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3.00'],
            'add negative number to a positive' => ['-1', '2', '1.00'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
        ];
    }
}
