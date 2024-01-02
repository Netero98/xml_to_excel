<?php

namespace App\Exceptions;

class DbTransactionRolledBackException extends \Exception
{
    public function __construct(string $message = "DB transaction is rolled back", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
