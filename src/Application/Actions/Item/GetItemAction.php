<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\ItemNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetItemAction extends ItemAction
{

    /**
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