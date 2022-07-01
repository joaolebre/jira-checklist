<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateUserPasswordAction extends UserAction
{

    /**
     * @OA\Patch(
     *     tags={"User"},
     *     path="/api/users/password/{id}",
     *     summary="Update the password of a specific user",
     *     operationId="updateUserPassword",
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
     *         description="User password updated"
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
     *         description="Password",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"password"},
     *              @OA\Property(property="password", type="string", format="text", example="password123456")
     *         )
     *     )
     * )
     * @return Response
     * @throws UserValidationException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $userId = $this->resolveArg('id');
        $password = $this->request->getParsedBody()['password'];

        User::validateUserData('Valid Name', 'validemail@email.com', $password);

        $this->userRepository->updateUserPassword((int) $userId, $password);

        $this->logger->info("Password of user with id `{$userId}` was updated.");

        return $this->respondWithData();
    }
}