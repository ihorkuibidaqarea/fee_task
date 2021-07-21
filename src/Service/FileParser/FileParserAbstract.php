<?php

namespace Src\Service\FileParser;


abstract class FileParserAbstract
{
    private $fileName;
    private $delimiter;

    
    public function __construct($fileName, $delimiter = ',')
    {
        $this->fileName = $fileName;
        $this->delimiter = $delimiter;
    }


    public function data()
    {}
}
