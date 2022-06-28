<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateSectionAction extends SectionAction
{

    /**
     * @return Response
     * @throws SectionNotFoundException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $section = $this->sectionRepository->findSectionById($sectionId);

        $data = $this->request->getParsedBody();

        $section->setName($data['name']);
        $section->setOrder((int) $data['order']);

        $this->sectionRepository->updateSection($section);

        $this->logger->info("Section with id `{$sectionId}` was updated.");

        return $this->respondWithData($section)->withHeader('Location', "/sections/{$sectionId}");
    }
}