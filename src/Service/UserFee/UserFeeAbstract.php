<?php

namespace App\Service\UserFee;

use App\Service\FileParser\FileParserAbstract;
use App\Service\Exchange\Interfaces\ChangeMoneyInterface;
use App\Repository\Interfaces\UserRepositoryAbstract;


abstract class UserFeeAbstract
{
    private $data;
   

    public function __construct(FileParserAbstract $parser, ChangeMoneyInterface $exchange, UserRepositoryAbstract $repository)
    {
        $this->data = $parser->data();
    }
    

    public function getFee()
    {}
}
