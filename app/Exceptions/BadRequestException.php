<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
    protected $message = 'Bad request';
    protected $code    = Response::HTTP_BAD_REQUEST;

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->code);
    }
}
