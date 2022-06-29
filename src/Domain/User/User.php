<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
