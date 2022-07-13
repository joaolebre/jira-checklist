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
    private $is_checked;

    /**
     * Check if item is important,
     * @var bool
     * @OA\Property ()
     */
    private $is_important;

    /**
     * Position of the item in the section,
     * @var int
     * @OA\Property ()
     */
    private $position;

    /**
     * Item section id,
     * @var int
     * @OA\Property ()
     */
    private $section_id;

    /**
     * Item status id,
     * @var int
     * @OA\Property ()
     */
    private $status_id;


    /**
     * @throws ItemValidationException
     */
    public static function validateItemData($request, $data) {
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PUT') {
            try {
                v::stringVal()->assert($data['summary']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Summary must be a string.');
            }
        }

        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PATCH') {
            try {
                v::number()->assert($data['section_id']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Section id must be a number.');
            }
        }

        if ($request->getMethod() == 'PUT') {
            try {
                v::boolVal()->assert($data['is_checked']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Is checked must evaluate to a boolean value.');
            }

            try {
                v::boolVal()->assert($data['is_important']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Is important must evaluate to a boolean value.');
            }

            try {
                v::number()->assert($data['position']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Position must be a number.');
            }

            try {
                v::number()->assert($data['status_id']);
            } catch (NestedValidationException $ex) {
                throw new ItemValidationException($request, 'Status id must be a number.');
            }
        }
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
     * @param bool $is_checked
     */
    public function setIsChecked(bool $is_checked): void
    {
        $this->is_checked = $is_checked;
    }

    /**
     * @param bool $is_important
     */
    public function setIsImportant(bool $is_important): void
    {
        $this->is_important = $is_important;
    }

    /**
     * @param int $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
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
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @param int $section_id
     */
    public function setSectionId(int $section_id): void
    {
        $this->section_id = $section_id;
    }

    /**
     * @return bool
     */
    public function getIsChecked(): bool
    {
        return $this->is_checked;
    }

    /**
     * @return bool
     */
    public function getIsImportant(): bool
    {
        return $this->is_important;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->section_id;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->status_id;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => (int) $this->id,
            'summary' => $this->summary,
            'is_checked' => (bool) $this->is_checked,
            'is_important' => (bool) $this->is_important,
            'position' => (int) $this->position,
            'section_id' => (int) $this->section_id,
            'status_id' => (int) $this->status_id
        ];
    }
}