<?php
declare(strict_types=1);

namespace App\Application\Actions\ItemStatus;

use App\Domain\ItemStatus\ItemStatusNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetItemStatusAction extends ItemStatusAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws ItemStatusNotFoundException
     */
    protected function action(): Response
    {
        $itemStatusId = (int) $this->resolveArg('id');
        $itemStatus = $this->itemStatusRepository->findItemStatusById($itemStatusId);

        $this->logger->info("Item status of id `${itemStatusId}` was viewed.");

        return $this->respondWithData($itemStatus);
    }
}