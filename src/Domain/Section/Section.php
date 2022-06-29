<?php
declare(strict_types=1);

namespace App\Domain\Section;

use JsonSerializable;

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
     * Order of section in the tab,
     * @var int
     * @OA\Property ()
     */
    private $order;

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
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @param int $tabId
     */
    public function setTabId(int $tabId): void
    {
        $this->tabId = $tabId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getOrder(): int
    {
        return $this->order;
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
            'order' => $this->order,
            'tab_id' => $this->tab_id,
            'items' => $this->items
        ];
    }
}