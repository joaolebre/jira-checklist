<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateItemAction extends ItemAction
{

    /**
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

        $this->itemRepository->updateItem($item);

        $this->logger->info("Item with id `{$itemId}` was updated.");

        return $this->respondWithData($item)->withHeader('Location', "/items/{$itemId}");
    }
}