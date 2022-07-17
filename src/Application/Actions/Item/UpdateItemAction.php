<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use App\Domain\Item\ItemValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateItemAction extends ItemAction
{

    /**
     * @OA\Put(
     *     tags={"Item"},
     *     path="/api/items/{id}",
     *     summary="Update a specific item",
     *     operationId="updateItem",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Item updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     ),
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
     *              required={"summary","is_checked","is_important","order","status_id"},
     *              @OA\Property(property="summary", type="string", format="text", example="This is an item summary"),
     *              @OA\Property(property="is_checked", type="boolean", example=false),
     *              @OA\Property(property="is_important", type="boolean", example=true),
     *              @OA\Property(property="position", type="integer", format="int64", example=6),
     *              @OA\Property(property="status_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws ItemNotFoundException
     * @throws ItemValidationException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($itemId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this item.');
        }

        $item = $this->itemRepository->findItemById($itemId);

        $data = $this->request->getParsedBody();

        Item::validateItemData($this->request, $data);

        $item->setSummary($data['summary']);
        $item->setIsChecked($data['is_checked']);
        $item->setIsImportant($data['is_important']);
        $item->setPosition($data['position']);
        $item->setStatusId($data['status_id']);

        $item = $this->itemRepository->updateItem($item);

        $this->logger->info("Item with id `{$itemId}` was updated.");

        return $this->respondWithData($item);
    }
}