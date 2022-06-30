<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteUserAction extends UserAction
{

    /**
     * @OA\Delete(
     *     tags={"User"},
     *     path="/api/users/{id}",
     *     summary="Deletes a user",
     *     operationId="deleteUser",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="User id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     )
     * )
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