<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $passwordHash;
    private ?DateTimeImmutable $emailVerifiedAt;

    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;


    public function __construct(
        string $name,
        string $email,
        string $passwordHash,
        ?int $id = null,
        ?DateTimeImmutable $emailVerifiedAt = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getEmailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeName(string $newName): void
    {

        $this->name = $newName;
        $this->touchUpdatedAt();
    }

    public function changeEmail(string $newEmail): void
    {

        $this->email = $newEmail;
        $this->emailVerifiedAt = null;
        $this->touchUpdatedAt();
    }


    public function changePassword(string $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
        $this->touchUpdatedAt();
    }

    public function markEmailAsVerified(): void
    {
        if ($this->emailVerifiedAt === null) {
            $this->emailVerifiedAt = new DateTimeImmutable();
            $this->touchUpdatedAt();
        }
    }


    public function setId(int $id): void
    {
        if ($this->id !== null) {
        }
        $this->id = $id;
    }

    private function touchUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
