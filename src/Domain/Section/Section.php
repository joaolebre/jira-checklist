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
    private $tab_id;

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
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PUT') {
            try {
                v::stringType()->assert($data['name']);
            } catch (NestedValidationException $ex) {
                throw new SectionValidationException($request, 'Name must be a string.');
            }

        }

        if ($request->getMethod() == 'PUT') {
            try {
                v::number()->assert($data['position']);
            } catch (NestedValidationException $ex) {
                throw new SectionValidationException($request, 'Position must be a number.');
            }
        }

        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PATCH') {
            try {
                v::number()->assert($data['tab_id']);
            } catch (NestedValidationException $ex) {
                throw new SectionValidationException($request, 'Tab id must be a number.');
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
     * @param int $tab_id
     */
    public function setTabId(int $tab_id): void
    {
        $this->tab_id = $tab_id;
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
        return (int) $this->position;
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
        return (int) $this->tab_id;
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

        if ($this->items != null) {
            return [
                'id' => (int) $this->id,
                'name' => $this->name,
                'position' => (int) $this->position,
                'tab_id' => (int) $this->tab_id,
                'items' => $this->items
            ];
        } else {
            return [
                'id' => (int) $this->id,
                'name' => $this->name,
                'position' => (int) $this->position,
                'tab_id' => (int) $this->tab_id
            ];
        }
    }
}