<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use PDO;

class SectionRepository extends BaseRepository
{
    public function findAll(): array {
        $query = 'SELECT id, name, position, tab_id FROM sections';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Section\Section');
    }

    public function findAllByUserId(int $userId): array {
        $query = '
            SELECT * FROM sections
            WHERE tab_id IN
                (SELECT id FROM tabs
                WHERE ticket_id IN
                    (SELECT id FROM tickets
                    WHERE user_id = :user_id
                ))
        ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Section\Section');
    }

    /**
     * @throws SectionNotFoundException
     */
    public function findSectionById(int $sectionId): Section {
        $query = 'SELECT * FROM sections WHERE sections.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $sectionId);
        $statement->execute();

        $section = $statement->fetchObject(Section::class);

        if (! $section) {
            throw new SectionNotFoundException();
        }

        return $section;
    }

    public function findSectionsByTabId(int $tabId): array {
        $query = '
            SELECT id, name, position FROM sections 
            WHERE sections.tab_id = :tab_id 
            ORDER BY sections.position
            ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':tab_id', $tabId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Section\Section');
    }

    /**
     * @throws SectionNotFoundException
     */
    public function createSection(Section $section): Section {
        $query = '
            INSERT INTO sections(name, position, tab_id)
            SELECT :name, MAX(position) + 1, :tab_id FROM sections
            WHERE tab_id = :tab_id
        ';
        $statement = $this->database->prepare($query);

        $name = $section->getName();
        $tabId = $section->getTabId();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':tab_id', $tabId);

        $statement->execute();

        return $this->findSectionById((int) $this->database->lastInsertId());
    }

    public function updateSection(Section $section): Section {
        $query = '
            UPDATE sections
            SET name = :name,
                position = :position
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $sectionId = $section->getId();
        $name = $section->getName();
        $position = $section->getPosition();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':position', $position);
        $statement->bindParam(':id', $sectionId);

        $statement->execute();

        return $section;
    }

    /**
     * @throws SectionNotFoundException
     */
    public function updateSectionTab(int $sectionId, int $tabId): Section
    {
        $query = '
            UPDATE sections
            SET tab_id = :tab_id
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $statement->bindParam(':id', $sectionId);
        $statement->bindParam(':tab_id', $tabId);

        $statement->execute();

        return $this->findSectionById((int) $sectionId);
    }

    /**
     * @throws SectionNotFoundException
     */
    public function deleteSectionById(int $sectionId) {
        $this->findSectionById($sectionId);

        $query = 'DELETE FROM sections WHERE sections.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $sectionId);
        $statement->execute();
    }
}