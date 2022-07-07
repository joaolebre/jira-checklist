<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Slim\Exception\HttpBadRequestException;

class UpdateUserPasswordAction extends UserAction
{

    /**
     * @OA\Patch(
     *     tags={"User"},
     *     path="/api/users/password/{id}",
     *     summary="Update the password of a specific user",
     *     operationId="updateUserPassword",
     *     security={
     *           {"bearerAuth": {}}
     *       },
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
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Password",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"password"},
     *              @OA\Property(property="password", type="string", format="text", example="Password123456?")
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

        try {
            v::regex('^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$^')->assert($password);
        } catch (NestedValidationException $ex) {
            throw new UserValidationException($this->request, 'Password must have at least 8 characters, 1 lowercase letter, 1 uppercase letter, 1 number and 1 symbol.');
        }

        $this->userRepository->updateUserPassword((int) $userId, $password);

        $this->logger->info("Password of user with id `{$userId}` was updated.");

        return $this->respondWithData();
    }
}