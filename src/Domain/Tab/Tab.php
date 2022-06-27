<?php
declare(strict_types=1);

namespace App\Domain\Tab;

use JsonSerializable;

class Tab implements JsonSerializable
{

    private $id;
    private $name;
    private $order;
    private $ticketId;
    private $sections;

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
    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'ticket_id' => $this->ticketId,
            'sections' => $this->sections
        ];
    }
}