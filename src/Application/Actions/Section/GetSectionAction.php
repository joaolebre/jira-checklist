<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetSectionAction extends SectionAction
{

    /**
     * @return Response
     * @throws SectionNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $section = $this->sectionRepository->findSectionById($sectionId);

        $section->setItems($this->itemRepository->findItemsBySectionId($sectionId));

        $this->logger->info("Section with id `${sectionId}` was viewed.");

        return $this->respondWithData($section);
    }
}