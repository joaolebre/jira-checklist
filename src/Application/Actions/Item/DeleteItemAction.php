<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteItemAction extends ItemAction
{

    /**
     * @OA\Delete(
     *     tags={"Item"},
     *     path="/api/items/{id}",
     *     summary="Deletes a item",
     *     operationId="deleteItem",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     * )
     * @return Response
     * @throws HttpBadRequestException|ItemNotFoundException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');
        $this->itemRepository->deleteItemById($itemId);

        $this->logger->info("Item with id `${itemId} deleted successfully`.");

        return $this->respondWithData();
    }
}