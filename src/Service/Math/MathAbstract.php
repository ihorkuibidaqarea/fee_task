<?php

namespace App\Service\Math;


abstract class MathAbstract
{
    private $scale;


    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }


    abstract public function add(string $leftOperand, string $rightOperand): string;
    

    abstract public function subtract(string $leftOperand, string $rightOperand): string;


    abstract public function multiply(string $leftOperand, string $rightOperand): string;


    abstract public function compare(string $leftOperand, string $rightOperand): int;
    

    abstract public function divide(string $leftOperand, string $rightOperand): string;
}
