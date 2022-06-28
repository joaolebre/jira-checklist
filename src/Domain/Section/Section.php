<?php
declare(strict_types=1);

namespace App\Domain\Section;

use JsonSerializable;

class Section implements JsonSerializable
{

    private $id;
    private $name;
    private $order;
    private $tabId;
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