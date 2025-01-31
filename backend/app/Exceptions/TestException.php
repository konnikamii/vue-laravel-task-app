<?php

namespace App\Exceptions;

class TestException extends CustomException
{
    public static function forTest(): self
    {
        return new self('This is a test exception', 403);
    }

    public static function siteIsDown(): self
    {
        return new self('Site is down!', 500);
    }
}
