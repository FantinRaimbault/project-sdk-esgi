<?php

namespace App\Sdk\Exceptions;

class SdkException extends \Exception
{    
    /**
     * __construct
     *
     * @param  mixed $message
     * @param  mixed $code
     * @param  mixed $previous
     * @return void
     */
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}