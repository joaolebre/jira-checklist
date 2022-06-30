<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetItemAction extends ItemAction
{

    /**
     * @OA\Get(
     *     tags={"Item"},
     *     path="/api/items/{id}",
     *     summary="Get a item by id",
     *     operationId="getItemById",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single item.",
     *          @OA\JsonContent(ref="#/components/schemas/Item")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Item not found."
     *      )
     * )
     * @return Response
     * @throws ItemNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');
        $item = $this->itemRepository->findItemById($itemId);

        $this->logger->info("Item of id `${itemId}` was viewed.");

        return $this->respondWithData($item);
    }
}