<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
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

        [
            'summary' => $summary,
            'is_checked' => $isChecked,
            'is_important' => $isImportant,
            'order' => $order,
            'status_id' => $statusId
        ] = $data;

        $item->setSummary($summary);
        $item->setIsChecked($isChecked);
        $item->setIsImportant($isImportant);
        $item->setOrder($order);
        $item->setStatusId($statusId);

        $this->itemRepository->updateItem($item);

        $this->logger->info("Item with id `{$itemId}` was updated.");

        return $this->respondWithData($item, 201)->withHeader('Location', "/items/{$itemId}");
    }
}