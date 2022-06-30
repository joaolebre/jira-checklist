<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateItemAction extends ItemAction
{

    /**
     * @OA\Post(
     *     tags={"Item"},
     *     path="/api/items",
     *     summary="Create a new item",
     *     operationId="createItem",
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\RequestBody(
     *         description="Item object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"summary","order","section_id"},
     *              @OA\Property(property="summary", type="string", format="text", example="This is an item summary"),
     *              @OA\Property(property="order", type="integer", format="int64", example=6),
     *              @OA\Property(property="section_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws ItemNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $newItem = Item::fromJSON($data);
        $createdItem = $this->itemRepository->createItem($newItem);

        $this->logger->info("New item with id `{$createdItem->getId()}` was created.");

        return $this->respondWithData($createdItem, 201)->withHeader('Location', "/items/{$createdItem->getId()}");
    }
}