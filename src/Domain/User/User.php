<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * @OA\Schema ()
 */
class User implements JsonSerializable
{
    /**
     * User id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * User name,
     * @var string
     * @OA\Property ()
     */
    private $name;

    /**
     * User email,
     * @var string
     * @OA\Property ()
     */
    private $email;

    /**
     * User password,
     * @var string
     * @OA\Property ()
     */
    private $password;

    /**
     * @throws UserValidationException
     */
    public static function validateUserData($name, $email, $password) {
        try {
            v::stringVal()->length(3, 70)->assert($name);
        } catch (NestedValidationException $ex) {
            throw new UserValidationException('Name must be between 3 and 70 characters.', 405);
        }

        try {
            v::email()->assert($email);
        } catch (NestedValidationException $ex) {
            throw new UserValidationException('Email format is invalid.', 405);
        }

        try {
            v::regex('^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$^')->assert($password);
        } catch (NestedValidationException $ex) {
            throw new UserValidationException('Password must have at least 8 characters,
                1 lowercase letter, 1 uppercase letter, 1 number and 1 symbol.', 405);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }



    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
