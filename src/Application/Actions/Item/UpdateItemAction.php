<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateItemAction extends ItemAction
{

    /**
     * @OA\Put(
     *     tags={"Item"},
     *     path="/api/items/{id}",
     *     summary="Update a specific item",
     *     operationId="updateItem",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Item updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         description="Item object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"summary","is_checked","is_important","order","status_id"},
     *              @OA\Property(property="summary", type="string", format="text", example="This is an item summary"),
     *              @OA\Property(property="is_checked", type="boolean", example=false),
     *              @OA\Property(property="is_important", type="boolean", example=true),
     *              @OA\Property(property="order", type="integer", format="int64", example=6),
     *              @OA\Property(property="status_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws ItemNotFoundException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');
        $item = $this->itemRepository->findItemById($itemId);

        $data = $this->request->getParsedBody();

        $item->setSummary($data['summary']);
        $item->setIsChecked($data['is_checked']);
        $item->setIsImportant($data['is_important']);
        $item->setOrder($data['order']);
        $item->setStatusId($data['status_id']);

        $item = $this->itemRepository->updateItem($item);

        $this->logger->info("Item with id `{$itemId}` was updated.");

        return $this->respondWithData($item)->withHeader('Location', "/items/{$itemId}");
    }
}