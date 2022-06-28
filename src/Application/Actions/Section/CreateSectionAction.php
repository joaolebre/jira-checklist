<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateSectionAction extends SectionAction
{

    /**
     * @return Response
     * @throws SectionNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $newSection = Section::fromJSON($data);
        $createdSection = $this->sectionRepository->createSection($newSection);

        $this->logger->info("New section with id `{$createdSection->getId()}` was created.");

        return $this->respondWithData($createdSection, 201)->withHeader('Location', "/sections/{$createdSection->getId()}");
    }
}