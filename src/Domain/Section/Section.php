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
     * @return int|null
     */
    public function getId(): ?int
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
     * @return string
     */
    public function getTabId(): string
    {
        return $this->tabId;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'tab_id' => $this->tabId,
            'items' => $this->items
        ];
    }
}