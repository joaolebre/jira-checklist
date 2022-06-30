<?php
declare(strict_types=1);

namespace App\Application\Actions\ItemStatus;

use App\Domain\ItemStatus\ItemStatusNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetItemStatusAction extends ItemStatusAction
{

    /**
     * @OA\Get(
     *     tags={"Item Status"},
     *     path="/api/item-statuses/{id}",
     *     summary="Get a item status by id",
     *     operationId="getItemStatusById",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item status id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single item status.",
     *          @OA\JsonContent(ref="#/components/schemas/ItemStatus")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Item status not found."
     *      )
     * )
     * @return Response
     * @throws ItemStatusNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $itemStatusId = (int) $this->resolveArg('id');
        $itemStatus = $this->itemStatusRepository->findItemStatusById($itemStatusId);

        $this->logger->info("Item status with id `${itemStatusId}` was viewed.");

        return $this->respondWithData($itemStatus);
    }
}