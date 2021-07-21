<?php

declare(strict_types=1);

namespace App\Service\FileParser;

use App\Entity\Operaiton;


class CsvParser extends FileParserAbstract
{
    private $fileName;
    private $delimiter;


    public function __construct($fileName, $delimiter = ',')
    {
        $this->fileName = $fileName;
        $this->delimiter = $delimiter;
    }


    public function data()
    {
        $filePath = __DIR__ .'/../../../var/data/'. $this->fileName;       
        if (!file_exists($filePath) || !is_readable($filePath))
            return false;
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
                yield new Operaiton($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
            }
            fclose($handle);
        }
        
    }
}
