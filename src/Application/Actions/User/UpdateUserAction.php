<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateUserAction extends UserAction
{

    /**
     * @OA\Put(
     *     tags={"User"},
     *     path="/api/users/{id}",
     *     summary="Update a specific user",
     *     operationId="updateUser",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="User id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         description="User object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", format="text", example="João"),
     *              @OA\Property(property="email", type="string", format="text", example="joao@gmail.com"),
     *              @OA\Property(property="password", type="string", format="text", example="password123456")
     *         )
     *     )
     * )
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