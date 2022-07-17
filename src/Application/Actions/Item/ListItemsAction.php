<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class ListItemsAction extends ItemAction
{

    /**
     * @OA\Get(
     *     tags={"Item"},
     *     path="/api/items",
     *     summary="Get a list of all items",
     *     operationId="listItems",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=false,
     *          description="Set the user id to filter the items (admin only).",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List all items.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Item")
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
                $items = $this->itemRepository->findAllByUserId($userIdFilter);
            } else {
                $items = $this->itemRepository->findAll();
            }
        } else {
            $items = $this->itemRepository->findAllByUserId($userId);
        }

        $this->logger->info("Item list was viewed by ${userRole} with the id ${userId}.");

        return $this->respondWithData($items);
    }
}