<?php
declare(strict_types=1);

namespace App\Domain\ItemStatus;

use JsonSerializable;

class ItemStatus implements JsonSerializable {

    private $id;
    private $label;
    private $color;

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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }



    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'color' => $this->color
        ];
    }
}