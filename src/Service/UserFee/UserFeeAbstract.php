<?php

namespace Src\Service\UserFee;

use Src\Service\FileParser\FileParserAbstract;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;

abstract class UserFeeAbstract {

    private $data;
   

    public function __construct( FileParserAbstract $parser, ChangeMoneyInterface $exchange  ){

        $this->data = $parser->data();       
        
    }

    public function getFee(){}

}