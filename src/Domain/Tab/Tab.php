<?php
declare(strict_types=1);

namespace App\Domain\Tab;

use JsonSerializable;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * @OA\Schema ()
 */
class Tab implements JsonSerializable
{

    /**
     * Tab id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * Tab name,
     * @var string
     * @OA\Property ()
     */
    private $name;

    /**
     * Order of the tab in the ticket,
     * @var int
     * @OA\Property ()
     */
    private $order;

    /**
     * Tab ticket id,
     * @var int
     * @OA\Property ()
     */
    private $ticketId;

    /**
     * Tab sections list,
     * @var array
     * @OA\Property (@OA\Items(ref="#/components/schemas/Section"))
     */
    private $sections;

    /**
     * @throws TabValidationException
     */
    public static function validateTabData($name, $order, $ticketId) {
        try {
            v::stringVal()->assert($name);
        } catch (NestedValidationException $ex) {
            throw new TabValidationException('Name must be a string.', 405);
        }

        try {
            v::number()->assert($order);
        } catch (NestedValidationException $ex) {
            throw new TabValidationException('Order must be an integer.', 405);
        }

        try {
            v::number()->assert($ticketId);
        } catch (NestedValidationException $ex) {
            throw new TabValidationException('Ticket id must be an integer.', 405);
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