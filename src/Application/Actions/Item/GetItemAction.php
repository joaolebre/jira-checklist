<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class GetItemAction extends ItemAction
{

    /**
     * @OA\Get(
     *     tags={"Item"},
     *     path="/api/items/{id}",
     *     summary="Get a item by id",
     *     operationId="getItemById",
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
     *      @OA\Response(
     *          response=200,
     *          description="Get a single item.",
     *          @OA\JsonContent(ref="#/components/schemas/Item")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Item not found."
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     * )
     * @return Response
     * @throws ItemNotFoundException|HttpBadRequestException|HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($itemId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to view this item.');
        }

        $item = $this->itemRepository->findItemById($itemId);

        $this->logger->info("Item of id `${itemId}` was viewed.");

        return $this->respondWithData($item);
    }
}