<?php

namespace Src\Service\FileParser;

abstract class FileParserAbstract {

    public $fileName;

    public function __construct($fileName){

        $this->fileName = $fileName;

    }


    public function data(){

        $filePath = __DIR__.'/../../Files/'.$this->fileName;
       
        if (!file_exists( $filePath ) || !is_readable( $filePath ))
            return false;

        $header = null;
        $data = array();

        if (($handle = fopen($filePath, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                $data[] = array_combine(['date', 'user_id', 'account_type', 'transaction', 'amount', 'currency' ], $row);
            }
            fclose($handle);
        }

        return $data;
    }

}