<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/users",
     *     summary="Get a list of all users",
     *     operationId="listUsers",
     *      @OA\Response(
     *          response=200,
     *          description="List all users.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/User")
     *          )
     *      )
     * )
     */
    protected function action(): Response
    {
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
