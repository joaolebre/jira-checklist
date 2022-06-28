<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetTabAction extends TabAction
{

    /**
     * @return Response
     * @throws TabNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');
        $tab = $this->tabRepository->findTabById($tabId);

        $tab->setSections($this->sectionRepository->findSectionsByTabId($tabId));

        $this->logger->info("Tab with id `${tabId}` was viewed.");

        return $this->respondWithData($tab);
    }
}