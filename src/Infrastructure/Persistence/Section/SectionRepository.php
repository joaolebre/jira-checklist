<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class SectionRepository extends AbstractRepository
{
    public function findAll(): array {
        $query = 'SELECT * FROM sections';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
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
            SELECT * FROM sections 
            WHERE sections.tab_id = :tab_id 
            ORDER BY sections.`order`
            ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':tab_id', $tabId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws SectionNotFoundException
     */
    public function createSection(Section $section): Section {
        $query = '
            INSERT INTO sections(name, `order`, tab_id)
            VALUES (:name, :order, :tab_id)
        ';
        $statement = $this->database->prepare($query);

        $name = $section->getName();
        $order = $section->getOrder();
        $tabId = $section->getTabId();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':order', $order);
        $statement->bindParam(':tab_id', $tabId);

        $statement->execute();

        return $this->findSectionById((int) $this->database->lastInsertId());
    }

    public function updateSection(Section $section): Section {
        $query = '
            UPDATE sections
            SET name = :name,
                `order` = :order
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $sectionId = $section->getId();
        $name = $section->getName();
        $order = $section->getOrder();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':order', $order);
        $statement->bindParam(':id', $sectionId);

        $statement->execute();

        return $section;
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