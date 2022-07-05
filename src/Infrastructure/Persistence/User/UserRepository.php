<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserLoginFailedException;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use PDO;

class UserRepository extends BaseRepository
{

    public function findAll(): array {
        $query = 'SELECT name, email, password FROM users';
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
     * @throws UserLoginFailedException
     */
    public function loginUser(string $email, string $password) {
        $query = '
            SELECT * FROM users
            WHERE email = :email
        ';
        $statement = $this->database->prepare($query);

        $statement->bindParam(':email', $email);

        $statement->execute();

        $user = $statement->fetchObject(User::class);

        if (! $user) {
            throw new UserLoginFailedException();
        } else {
            $hashedPassword = $user->getPassword();

            if (! password_verify($password, $hashedPassword)) {
                throw new UserLoginFailedException();
            }
        }

        return $user;
    }

    public function isEmailUnique(string $email): bool {
        $query = '
            SELECT * FROM users
            WHERE email = :email
        ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        return ! $statement->fetchObject(User::class);
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

    public function updateUserPassword(int $userId, string $password) {
        $query = '
            UPDATE users
            SET password = :password
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $statement->bindParam(':id', $userId);
        $statement->bindParam(':password', $hashed_password);

        $statement->execute();
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