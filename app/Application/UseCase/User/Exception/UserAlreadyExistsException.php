<?php
declare(strict_types=1);
namespace App\Application\UseCase\User\Exception;
class UserAlreadyExistsException extends \RuntimeException
{
    public function __construct(string $message = 'User already exists.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
