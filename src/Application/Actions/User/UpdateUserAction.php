<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateUserAction extends UserAction
{

    /**
     * @OA\Put(
     *     tags={"User"},
     *     path="/api/users/{id}",
     *     summary="Update a specific user",
     *     operationId="updateUser",
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
     *     @OA\Response(
     *         response=200,
     *         description="User updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="User object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","email"},
     *              @OA\Property(property="name", type="string", format="text", example="João"),
     *              @OA\Property(property="email", type="string", format="text", example="joao@gmail.com")
     *         )
     *     )
     * )
     * @return Response
     * @throws UserNotFoundException
     * @throws HttpBadRequestException
     * @throws UserValidationException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($userId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this user.');
        }

        $user = $this->userRepository->findUserById($userId);

        $data = $this->request->getParsedBody();

        User::validateUserData($this->request, $data);

        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $this->userRepository->updateUser($user);

        $this->logger->info("User with id `{$userId}` was updated.");

        return $this->respondWithData($user);
    }
}