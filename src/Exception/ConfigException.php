<?php

namespace App\Exception;

use Exception;

class ConfigException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = 'ConfigException: '. $message;        
    }

    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
