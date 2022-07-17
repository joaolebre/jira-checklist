<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class GetUserAction extends UserAction
{
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/users/{id}",
     *     summary="Get a user by id",
     *     operationId="getUserById",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="User id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
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
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     * @throws HttpBadRequestException
     * @throws UserNotFoundException|HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($userId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to view this user.');
        }

        $user = $this->userRepository->findUserById($userId);

        $this->logger->info("User of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
