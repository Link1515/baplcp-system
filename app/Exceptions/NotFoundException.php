<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NotFoundException extends Exception
{
    protected $message = 'Resource not found';
    protected $code    = Response::HTTP_NOT_FOUND;

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->code);
    }
}
