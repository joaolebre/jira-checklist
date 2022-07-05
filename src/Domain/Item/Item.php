<?php
declare(strict_types=1);

namespace App\Domain\Item;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * @OA\Schema ()
 */
class Item implements JsonSerializable {

    /**
     * Item id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * Item summary,
     * @var string
     * @OA\Property ()
     */
    private $summary;

    /**
     * Check if item is checked,
     * @var bool
     * @OA\Property ()
     */
    private $isChecked;

    /**
     * Check if item is important,
     * @var bool
     * @OA\Property ()
     */
    private $isImportant;

    /**
     * Order of the item in the section,
     * @var int
     * @OA\Property ()
     */
    private $order;

    /**
     * Item section id,
     * @var int
     * @OA\Property ()
     */
    private $sectionId;

    /**
     * Item status id,
     * @var int
     * @OA\Property ()
     */
    private $statusId;

    /**
     * @throws ItemValidationException
     */
    public static function validateItemData($request, $summary, $isChecked, $isImportant, $order, $statusId, $sectionId) {
        try {
            v::stringVal()->assert($summary);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Summary must be a string.');
        }

        try {
            v::boolVal()->assert($isChecked);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Is checked must evaluate to a boolean value.');
        }

        try {
            v::boolVal()->assert($isImportant);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Is important must evaluate to a boolean value.');
        }

        try {
            v::number()->assert($order);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Order must be an integer.');
        }

        try {
            v::number()->assert($statusId);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Status id must be an integer.');
        }

        try {
            v::number()->assert($sectionId);
        } catch (NestedValidationException $ex) {
            throw new ItemValidationException($request, 'Section id must be an integer.');
        }
    }

    public static function fromJSON(array $data): Item
    {
        [
            'summary' => $summary,
            'order' => $order,
            'section_id' => $sectionId
        ] = $data;

        $newItem = new self();
        $newItem->setSummary($summary);
        $newItem->setOrder($order);
        $newItem->setSectionId($sectionId);

        return $newItem;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param bool $isChecked
     */
    public function setIsChecked(bool $isChecked): void
    {
        $this->isChecked = $isChecked;
    }

    /**
     * @param bool $isImportant
     */
    public function setIsImportant(bool $isImportant): void
    {
        $this->isImportant = $isImportant;
    }

    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId(int $sectionId): void
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @return bool
     */
    public function getIsChecked(): bool
    {
        return $this->isChecked;
    }

    /**
     * @return bool
     */
    public function getIsImportant(): bool
    {
        return $this->isImportant;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'summary' => $this->summary,
            'is_checked' => $this->is_checked,
            'is_important' => $this->is_important,
            'order' => $this->order,
            'section_id' => $this->section_id,
            'status_id' => $this->status_id
        ];
    }
}