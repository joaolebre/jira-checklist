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
    private $user_id;

    /**
     * Ticket tabs list,
     * @var array
     * @OA\Property (@OA\Items(ref="#/components/schemas/Tab"))
     */
    private $tabs;

    /**
     * @throws TicketValidationException
     */
    public static function validateTicketData($request, $data) {
        try {
            v::stringVal()->length(3)->assert($data['title']);
        } catch (NestedValidationException $ex) {
            throw new TicketValidationException($request, 'Title must be at least 3 characters.');
        }

        try {
            v::optional(v::stringType())->assert($data['description']);
        } catch (NestedValidationException $ex) {
            throw new TicketValidationException($request, 'Description must be a string.');
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
        return (int) $this->user_id;
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
        if ($this->tabs != null) {
            return [
                'id' => (int) $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => (int) $this->user_id,
                'tabs' => $this->tabs
            ];
        } else {
            return [
                'id' => (int) $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => (int) $this->user_id
            ];
        }
    }
}