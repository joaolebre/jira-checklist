<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{

    /**
     * @return Response
     * @throws UserNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $newUser = new User();
        $newUser->setName($data['name']);
        $newUser->setEmail($data['email']);
        $newUser->setPassword($data['password']);

        $createdUser = $this->userRepository->createUser($newUser);

        $this->logger->info("New user with id `{$createdUser->getId()}` was created.");

        return $this->respondWithData($createdUser, 201)->withHeader('Location', "/users/{$createdUser->getId()}");
    }
}