<?php

namespace Src\Service\UserFee;

use Src\Service\FileParser\FileParserAbstract;
use Src\Service\Exchange\Interfaces\ChangeMoneyInterface;
use Src\Repository\Interfaces\UserRepositoryAbstract;


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
