<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use Psr\Http\Message\ResponseInterface as Response;

class ListTabsAction extends TabAction
{

    protected function action(): Response
    {
        $tabs = $this->tabRepository->findAll();
        $this->logger->info("Tab list was viewed");

        return $this->respondWithData($tabs);
    }
}