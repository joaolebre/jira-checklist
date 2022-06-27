<?php
declare(strict_types=1);

namespace App\Domain\Item;

use App\Domain\ItemStatus\ItemStatus;
use App\Domain\Section\Section;
use JsonSerializable;
use phpDocumentor\Reflection\Types\Boolean;

class Item implements JsonSerializable {

    private $id;
    private $summary;
    private $isChecked;
    private $isImportant;
    private $order;
    private $section;
    private $status;

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
    public function getSummary(): string
    {
        return $this->summary;
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
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * @return ItemStatus
     */
    public function getStatus(): ItemStatus
    {
        return $this->status;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'summary' => $this->summary,
            'is_checked' => $this->isChecked,
            'is_important' => $this->isImportant,
            'order' => $this->order,
            'section' => $this->section,
            'status' => $this->status
        ];
    }
}