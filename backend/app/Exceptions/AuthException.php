<?php

namespace App\Exceptions;

class AuthException extends CustomException
{
    public static function invalidPassword(): self
    {
        return new self('Old password is incorrect', 422);
    }
}
