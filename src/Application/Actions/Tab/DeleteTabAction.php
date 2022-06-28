<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteTabAction extends TabAction
{

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $tabId = $this->resolveArg('id');
        $this->tabRepository->deleteTabById($tabId);

        $this->logger->info("Tab with id `${tabId} deleted successfully`.");

        return $this->respondWithData();
    }
}