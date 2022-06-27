<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteItemAction extends ItemAction
{

    /**
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