<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

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
        $items = $this->itemRepository->findAll();
        $this->logger->info("Item list was viewed");

        return $this->respondWithData($items);
    }
}