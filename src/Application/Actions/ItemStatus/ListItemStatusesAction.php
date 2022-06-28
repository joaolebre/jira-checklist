<?php
declare(strict_types=1);

namespace App\Application\Actions\ItemStatus;

use Psr\Http\Message\ResponseInterface as Response;

class ListItemStatusesAction extends ItemStatusAction
{

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $itemStatuses = $this->itemStatusRepository->findAll();
        $this->logger->info("Item status list was viewed");

        return $this->respondWithData($itemStatuses);
    }
}