<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTabAction extends TabAction
{

    /**
     * @return Response
     * @throws TabNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $tabId = $this->resolveArg('id');
        $tab = $this->tabRepository->findTabById((int) $tabId);

        $data = $this->request->getParsedBody();

        $tab->setName($data['name']);
        $tab->setOrder((int) $data['order']);

        $this->tabRepository->updateTab($tab);

        $this->logger->info("Tab with id `{$tabId}` was updated.");

        return $this->respondWithData($tab)->withHeader('Location', "/sections/{$tabId}");
    }
}