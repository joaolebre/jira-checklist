<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteSectionAction extends SectionAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws SectionNotFoundException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $this->sectionRepository->deleteSectionById($sectionId);

        $this->logger->info("Item with id `${sectionId} deleted successfully`.");

        return $this->respondWithData();
    }
}