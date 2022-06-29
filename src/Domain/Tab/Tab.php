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
     * @param int $ticketId
     */
    public function setTicketId(int $ticketId): void
    {
        $this->ticketId = $ticketId;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param array $sections
     */
    public function setSections(array $sections): void
    {
        $this->sections = $sections;
    }

    /**
     * @return int
     */
    public function getTicketId(): int
    {
        return $this->ticketId;
    }

    /**
     * @return null|array
     */
    public function getSections(): ?array
    {
        return $this->sections;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
            'ticket_id' => $this->ticket_id,
            'sections' => $this->sections
        ];
    }
}