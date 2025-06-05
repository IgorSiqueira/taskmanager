<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\FluentPDO\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Envms\FluentPDO\Query as QueryBuilder;
use PDOException;

class FluentPDOUserRepository implements UserRepositoryInterface
{
    private QueryBuilder $db;

    public function __construct(QueryBuilder $fluentPdoInstance)
    {
        $this->db = $fluentPdoInstance;
    }

    public function findById(int $id): ?User
    {
        try {
            $row = $this->db->from('users')
                ->where('id', $id)
                ->fetch();

            if (!$row) {
                return null;
            }

            return $this->mapRowToUser($row);
        } catch (PDOException $e) {
            error_log('Error finding user by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $row = $this->db->from('users')
                ->where('email', $email)
                ->fetch();

            if (!$row) {
                return null;
            }

            return $this->mapRowToUser($row);
        } catch (PDOException $e) {
            error_log('Error finding user by email: ' . $e->getMessage());
            return null;
        }
    }

    public function save(User $user): User
    {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPasswordHash(),
            'email_verified_at' => $user->getEmailVerifiedAt() ? $user->getEmailVerifiedAt()->format('Y-m-d H:i:s') : null,
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];

        try {
            if ($user->getId() === null) {
                $data['created_at'] = $user->getCreatedAt()->format('Y-m-d H:i:s');

                $newId = $this->db->insertInto('users', $data)->execute();

                if ($newId) {
                    $user->setId((int)$newId);
                } else {
                    throw new \RuntimeException('Failed to save new user: No ID returned.');
                }
            } else {
                $this->db->update('users', $data, $user->getId())->execute();
            }

            return $user;
        } catch (PDOException $e) {
            error_log('Error saving user: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(User $user): bool
    {
        if ($user->getId() === null) {
            return false;
        }

        try {
            $result = $this->db->deleteFrom('users', $user->getId())->execute();
            return $result !== false;
        } catch (PDOException $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }

    private function mapRowToUser(array $row): User
    {
        return new User(
            $row['name'],
            $row['email'],
            $row['password'],
            (int)$row['id'],
            $row['email_verified_at'] ? new DateTimeImmutable($row['email_verified_at']) : null,
            new DateTimeImmutable($row['created_at']),
            new DateTimeImmutable($row['updated_at']),
        );
    }
}
