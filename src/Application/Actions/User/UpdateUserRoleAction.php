<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class UpdateUserRoleAction extends UserAction
{

    /**
     * @OA\Patch(
     *     tags={"User"},
     *     path="/api/users/role/{id}",
     *     summary="Update the role of a specific user",
     *     operationId="updateUserRole",
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
     *         description="User role updated"
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
     *         description="User role",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"role"},
     *              @OA\Property(property="role", type="string", format="text", example="admin")
     *         )
     *     )
     * )
     * @inheritDoc
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        if (User::getLoggedInUserRole($this->request) != 'admin') {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify user roles.');
        }

        $userId = (int) $this->resolveArg('id');
        $data = $this->request->getParsedBody();

        $role = $this->request->getParsedBody()['role'];

        User::validateUserData($this->request, $data);

        $user = $this->userRepository->updateUserRole($userId, $role);

        $this->logger->info("Role of user with id `{$userId}` was updated to `{$role}`.");

        return $this->respondWithData($user);
    }
}