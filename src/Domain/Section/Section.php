<?php
declare(strict_types=1);

namespace App\Domain\Section;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * @OA\Schema ()
 */
class Section implements JsonSerializable
{

    /**
     * Section id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * Section name,
     * @var string
     * @OA\Property ()
     */
    private $name;

    /**
     * Position of section in the tab,
     * @var int
     * @OA\Property ()
     */
    private $position;

    /**
     * Section tab id,
     * @var int
     * @OA\Property ()
     */
    private $tabId;

    /**
     * Section items list,
     * @var array
     * @OA\Property (@OA\Items(ref="#/components/schemas/Item"))
     */
    private $items;

    /**
     * @throws SectionValidationException
     */
    public static function validateSectionData($request, $data) {
        try {
            v::stringVal()->assert($data['name']);
        } catch (NestedValidationException $ex) {
            throw new SectionValidationException($request, 'Name must be a string.');
        }

        try {
            v::number()->assert($data['position']);
        } catch (NestedValidationException $ex) {
            throw new SectionValidationException($request, 'Position must be an integer.');
        }

        if ($request->getMethod() == 'POST') {
            try {
                v::number()->assert($data['tab_id']);
            } catch (NestedValidationException $ex) {
                throw new SectionValidationException($request, 'Tab id must be an integer.');
            }
        }
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @param int $tabId
     */
    public function setTabId(int $tabId): void
    {
        $this->tabId = $tabId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getTabId(): int
    {
        return $this->tabId;
    }

    /**
     * @return null|array
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'tab_id' => $this->tab_id,
            'items' => $this->items
        ];
    }
}