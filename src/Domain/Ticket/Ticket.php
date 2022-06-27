<?php
declare(strict_types=1);

namespace App\Domain\Ticket;

use App\Domain\User\User;
use JsonSerializable;

class Ticket implements JsonSerializable
{

    private $id;
    private $name;
    private $user;
    private $tabs;

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
     * @return User
     */
    public function getUserId(): User
    {
        return $this->user;
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
           'user' => $this->user,
           'tabs' => $this->tabs
       ];
    }
}