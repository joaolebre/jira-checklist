<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetUserAction extends UserAction
{
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/users/{id}",
     *     summary="Get a user by id",
     *     operationId="getUserById",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="User id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single user.",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="User not found."
     *      )
     * )
     * @throws HttpBadRequestException
     * @throws UserNotFoundException
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserById($userId);

        $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
