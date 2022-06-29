<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteUserAction extends UserAction
{

    /**
     * @return Response
     * @throws UserNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $userId = $this->resolveArg('id');
        $this->userRepository->deleteUserById($userId);

        $this->logger->info("User with id `${userId} deleted successfully`.");

        return $this->respondWithData();
    }
}