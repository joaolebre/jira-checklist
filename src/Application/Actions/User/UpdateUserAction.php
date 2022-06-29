<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateUserAction extends UserAction
{

    /**
     * @return Response
     * @throws UserNotFoundException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $userId = $this->resolveArg('id');
        $user = $this->userRepository->findUserById((int) $userId);

        $data = $this->request->getParsedBody();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $this->userRepository->updateUser($user);

        $this->logger->info("User with id `{$userId}` was updated.");

        return $this->respondWithData($user)->withHeader('Location', "/users/{$userId}");
    }
}