<?php
declare(strict_types=1);

namespace App\Domain\Ticket;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * @OA\Schema ()
 */
class Ticket implements JsonSerializable
{

    /**
     * Ticket id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * Ticket title,
     * @var string
     * @OA\Property ()
     */
    private $title;

    /**
     * Ticket description,
     * @var string
     * @OA\Property ()
     */
    private $description;

    /**
     * User id of the user that created the ticket,
     * @var int
     * @OA\Property ()
     */
    private $userId;

    /**
     * Ticket tabs list,
     * @var array
     * @OA\Property (@OA\Items(ref="#/components/schemas/Tab"))
     */
    private $tabs;

    /**
     * @throws TicketValidationException
     */
    public static function validateTicketData($title, $description, $userId) {
        try {
            v::stringVal()->length(3)->assert($title);
        } catch (NestedValidationException $ex) {
            throw new TicketValidationException('Title must be at least 3 characters.', 405);
        }

        try {
            v::optional(v::stringVal())->assert($description);
        } catch (NestedValidationException $ex) {
            throw new TicketValidationException('Description must be a string.', 405);
        }

        try {
            v::number()->assert($userId);
        } catch (NestedValidationException $ex) {
            throw new TicketValidationException('User id must be an integer.', 405);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param array $tabs
     */
    public function setTabs(array $tabs): void
    {
        $this->tabs = $tabs;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    /**
     * @return int
     */
    public function getUserId(): int
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
           'title' => $this->title,
           'description' => $this->description,
           'user' => $this->user_id,
           'tabs' => $this->tabs
       ];
    }
}