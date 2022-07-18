<?php
declare(strict_types=1);

namespace App\Domain\User;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
     * User role,
     * @var string
     * @OA\Property ()
     */
    private $role;

    /**
     * @throws UserValidationException
     */
    public static function validateUserData($request, $data) {
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PUT') {
            try {
                v::stringType()->length(3, 70)->assert($data['name']);
            } catch (NestedValidationException $ex) {
                throw new UserValidationException($request, 'Name must be between 3 and 70 characters.');
            }

            try {
                v::email()->assert($data['email']);
            } catch (NestedValidationException $ex) {
                throw new UserValidationException($request, 'Email format is invalid.');
            }
        }

        if ($request->getMethod() == 'POST') {
            try {
                v::regex('^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$^')->assert($data['password']);
            } catch (NestedValidationException $ex) {
                throw new UserValidationException($request, 'Password must have at least 8 characters, 1 lowercase letter, 1 uppercase letter, 1 number and 1 symbol.');
            }
        }

        if ($request->getMethod() == 'PATCH') {
            try {
                v::anyOf(v::equals('admin'), v::equals('user'))->assert($data['role']);
            } catch (NestedValidationException $ex) {
                throw new UserValidationException($request, "Role must be either 'admin' or 'user'.");
            }
        }
    }

    public static function getLoggedInUserRole($request): String {
        $auth = substr($request->getHeaderLine('Authorization'), 7);
        $token = JWT::decode($auth, new Key($_ENV['SECRET'], 'HS256'));

        return $token->role;
    }

    public static function getLoggedInUserId($request): int {
        $auth = substr($request->getHeaderLine('Authorization'), 7);
        $token = JWT::decode($auth, new Key($_ENV['SECRET'], 'HS256'));

        return $token->sub;
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
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
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
            'id' => (int) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];
    }
}
