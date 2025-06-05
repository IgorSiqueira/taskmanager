<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\PasswordHasherInterface;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    private array $options;
    public function __construct(array $options = ['cost' => 12])
    {
        $this->options = $options;
    }
    public function hash(string $plainPassword): string
    {
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, $this->options);

        if ($hashedPassword === false) {
            throw new \RuntimeException('Password hashing failed.');
        }

        return $hashedPassword;
    }
    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}
