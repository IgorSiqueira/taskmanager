<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User; // Importa a entidade User que acabamos de criar

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): User;
    public function delete(User $user): bool;
}
