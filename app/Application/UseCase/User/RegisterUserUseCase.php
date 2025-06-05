<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Application\UseCase\User\Exception\UserAlreadyExistsException;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\PasswordHasherInterface;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Executa o caso de uso de registro de usuário.
     *
     * @param string $name O nome do usuário.
     * @param string $email O e-mail do usuário.
     * @param string $plainPassword A senha em texto plano.
     * @return User A entidade User recém-criada e salva.
     * @throws UserAlreadyExistsException Se um usuário com o e-mail fornecido já existir.
     * @throws \InvalidArgumentException Para entradas inválidas (ex: campos vazios, formato de e-mail inválido).
     */
    public function execute(string $name, string $email, string $plainPassword): User
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Name cannot be empty.');
        }
        if (empty(trim($email))) {
            throw new \InvalidArgumentException('Email cannot be empty.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format provided.');
        }
        if (empty($plainPassword)) { // Validação de força da senha poderia ser adicionada aqui
            throw new \InvalidArgumentException('Password cannot be empty.');
        }
        if (mb_strlen($plainPassword) < 8) { // Exemplo de regra de força de senha
            throw new \InvalidArgumentException('Password must be at least 8 characters long.');
        }
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException("A user with the email '{$email}' already exists.");
        }
        $passwordHash = $this->passwordHasher->hash($plainPassword);
        $user = new User(
            name: $name,
            email: $email,
            passwordHash: $passwordHash,
        );
        $savedUser = $this->userRepository->save($user);
        return $savedUser;
    }
}
