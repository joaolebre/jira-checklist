<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use Psr\Http\Message\ResponseInterface as Response;

class ListSectionsAction extends SectionAction
{

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $sections = $this->sectionRepository->findAll();

        $this->logger->info("Section list was viewed");

        return $this->respondWithData($sections);
    }
}