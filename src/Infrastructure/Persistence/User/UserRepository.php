<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class UserRepository extends AbstractRepository
{

    public function findAll(): array {
        $query = 'SELECT * FROM users';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserById(int $userId) {
        $query = 'SELECT * FROM users WHERE users.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $userId);
        $statement->execute();

        $user = $statement->fetchObject(User::class);

        if (! $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function createUser(User $user): User {
        $query = '
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ';
        $statement = $this->database->prepare($query);

        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement->bindParam(':name', $name);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashedPassword);

        $statement->execute();

        return $this->findUserById((int) $this->database->lastInsertId());
    }

    public function updateUser(User $user): User {
        $query = '
            UPDATE users
            SET name = :name,
                email = :email,
                password = :password
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $userId = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $statement->bindParam(':id', $userId);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashed_password);

        $statement->execute();

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUserById(int $userId) {
        $this->findUserById($userId);

        $query = 'DELETE FROM users WHERE users.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $userId);
        $statement->execute();
    }
}