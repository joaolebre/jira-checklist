<?php
declare(strict_types=1);

namespace App\Application\Actions\ItemStatus;

use Psr\Http\Message\ResponseInterface as Response;

class ListItemStatusesAction extends ItemStatusAction
{

    /**
     * @OA\Get(
     *     tags={"Item Status"},
     *     path="/api/item-statuses",
     *     summary="Get a list of all item statuses available",
     *     operationId="listItemStatuses",
     *      @OA\Response(
     *          response=200,
     *          description="List all item statuses.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ItemStatus")
     *          )
     *      )
     * )
     */
    protected function action(): Response
    {
        $itemStatuses = $this->itemStatusRepository->findAll();
        $this->logger->info("Item status list was viewed");

        return $this->respondWithData($itemStatuses);
    }
}