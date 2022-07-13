<?php
declare(strict_types=1);

namespace App\Domain\ItemStatus;

use JsonSerializable;

/**
 * @OA\Schema ()
 */
class ItemStatus implements JsonSerializable {

    /**
     * Status id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * Status label,
     * @var string
     * @OA\Property ()
     */
    private $label;

    /**
     * Status color,
     * @var string
     * @OA\Property ()
     */
    private $color;

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



    public function jsonSerialize(): array
    {
        return [
            'id' => (int) $this->id,
            'label' => $this->label,
            'color' => $this->color
        ];
    }
}