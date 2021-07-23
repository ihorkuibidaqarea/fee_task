<?php

namespace App\Service\FileParser;


abstract class FileParserAbstract
{
    private $fileName;
    private $delimiter;

    
    public function __construct(string $fileName, string $delimiter = ',')
    {
        $this->fileName = $fileName;
        $this->delimiter = $delimiter;
    }


    public function data()
    {}
}
