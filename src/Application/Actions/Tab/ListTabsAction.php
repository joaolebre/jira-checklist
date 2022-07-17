<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class ListTabsAction extends TabAction
{

    /**
     * @OA\Get(
     *     tags={"Tab"},
     *     path="/api/tabs",
     *     summary="Get a list of all tabs",
     *     operationId="listTabs",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=false,
     *          description="Set the user id to filter the tabs (admin only).",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List all tabs.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Tab")
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     */
    protected function action(): Response
    {
        $userId = User::getLoggedInUserId($this->request);
        $userRole = User::getLoggedInUserRole($this->request);

        if ($userRole == 'admin') {
            if (!empty($this->request->getQueryParams()['user_id'])) {
                $userIdFilter = (int) $this->request->getQueryParams()['user_id'];
                $tabs = $this->tabRepository->findAllByUserId($userIdFilter);
            } else {
                $tabs = $this->tabRepository->findAll();
            }
        } else {
            $tabs = $this->tabRepository->findAllByUserId($userId);
        }

        $this->logger->info("Tab list was viewed by ${userRole} with the id ${userId}.");

        return $this->respondWithData($tabs);
    }
}