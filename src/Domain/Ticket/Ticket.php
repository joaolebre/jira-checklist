<?php
declare(strict_types=1);

namespace App\Domain\Ticket;

use JsonSerializable;

class Ticket implements JsonSerializable
{

    private $id;
    private $name;
    private $userId;
    private $tabs;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $userId
     * @param string $tabs
     */
    public function __construct(?int $id, string $name, string $userId, string $tabs)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userId = $userId;
        $this->tabs = $tabs;
    }

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
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'userId' => $this->userId,
           'tabs' => $this->tabs
       ];
    }
}