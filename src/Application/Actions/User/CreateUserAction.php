<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserValidationException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/users/register",
     *     summary="Register a new user",
     *     operationId="createUser",
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\RequestBody(
     *         description="User object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", format="text", example="Pedro"),
     *              @OA\Property(property="email", type="email", format="text", example="pedro666@example.org"),
     *              @OA\Property(property="password", type="string", format="text", example="password123456")
     *         )
     *     )
     * )
     * @throws UserNotFoundException
     * @throws UserValidationException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        User::validateUserData($this->request, $data['name'], $data['email'], $data['password']);

        if (! $this->userRepository->isEmailUnique($data['email'])) {
            $this->logger->error("Email `{$data['email']}` already exists.");

            throw new UserValidationException($this->request, 'Email already exists.');
        }

        $newUser = new User();
        $newUser->setName($data['name']);
        $newUser->setEmail($data['email']);
        $newUser->setPassword($data['password']);

        $createdUser = $this->userRepository->createUser($newUser);

        $this->logger->info("New user with id `{$createdUser->getId()}` was created.");

        return $this->respondWithData($createdUser, 201)->withHeader('Location', "/users/{$createdUser->getId()}");
    }
}