<?php

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateItemAction extends ItemAction
{

    /**
     * @return Response
     * @throws ItemNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $newItem = Item::fromJSON($data);
        $createdItem = $this->itemRepository->createItem($newItem);

        $this->logger->info("New item with id `{$createdItem->getId()}`.");

        return $this->respondWithData($createdItem, 201)->withHeader('Location', "/items/{$createdItem->getId()}");
    }
}