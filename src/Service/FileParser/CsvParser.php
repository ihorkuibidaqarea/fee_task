<?php

namespace Src\Service\FileParser;

class CsvParser extends FileParserAbstract {

    public $fileName;
    public $delimiter;

    public function __construct( $fileName, $delimiter = ','){

        $this->fileName = $fileName;
        $this->delimiter = $delimiter;

    }

    public function data(){

        $filePath = __DIR__ .'/../../Files/'. $this->fileName;
       
        if (!file_exists( $filePath ) || !is_readable( $filePath ))
            return false;

        $header = null;

        if (($handle = fopen($filePath, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $this->delimiter)) !== false)
            {
                yield (object) array_combine(['date', 'user_id', 'account_type', 'transaction', 'amount', 'currency' ], $row);
            }
            fclose($handle);
        }

    }

}