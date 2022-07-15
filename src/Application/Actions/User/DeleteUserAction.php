<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserDeleteConflictException;
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
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Could not delete user because of database conflict"
     *     )
     * )
     * @return Response
     * @throws UserNotFoundException|HttpBadRequestException|UserDeleteConflictException
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        try {
            $this->userRepository->deleteUserById($userId);
        } catch (\PDOException $e) {
            $this->logger->error("While deleting section with id ${userId}: " . $e->getMessage());
            throw new UserDeleteConflictException($this->request);
        }

        $this->logger->info("User with id `${userId} deleted successfully`.");

        return $this->respondWithData();
    }
}