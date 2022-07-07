<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use App\Domain\Item\ItemValidationException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateItemAction extends ItemAction
{

    /**
     * @OA\Post(
     *     tags={"Item"},
     *     path="/api/items",
     *     summary="Create a new item",
     *     operationId="createItem",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Item object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"summary","order","section_id"},
     *              @OA\Property(property="summary", type="string", format="text", example="This is an item summary"),
     *              @OA\Property(property="position", type="integer", format="int64", example=6),
     *              @OA\Property(property="section_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws ItemNotFoundException|ItemValidationException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        Item::validateItemData($this->request, $data);
        $newItem = Item::fromJSON($data);
        $createdItem = $this->itemRepository->createItem($newItem);

        $this->logger->info("New item with id `{$createdItem->getId()}` was created.");

        return $this->respondWithData($createdItem, 201)->withHeader('Location', "/items/{$createdItem->getId()}");
    }
}