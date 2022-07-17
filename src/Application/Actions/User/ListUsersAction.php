<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class ListUsersAction extends UserAction
{
    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/users",
     *     summary="Get a list of all users",
     *     operationId="listUsers",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *      @OA\Response(
     *          response=200,
     *          description="List all users.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/User")
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        if (User::getLoggedInUserRole($this->request) != 'admin') {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to view the user list.');
        }

        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }
}
