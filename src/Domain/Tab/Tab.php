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
     * Position of the tab in the ticket,
     * @var int
     * @OA\Property ()
     */
    private $position;

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
    public static function validateTabData($request, $data) {
        try {
            v::stringVal()->assert($data['name']);
        } catch (NestedValidationException $ex) {
            throw new TabValidationException($request, 'Name must be a string.');
        }

        try {
            v::number()->assert($data['position']);
        } catch (NestedValidationException $ex) {
            throw new TabValidationException($request, 'Position must be an integer.');
        }

        if ($request->getMethod() == 'POST') {
            try {
                v::number()->assert($data['ticket_id']);
            } catch (NestedValidationException $ex) {
                throw new TabValidationException($request, 'Ticket id must be an integer.');
            }
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
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
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
    public function getPosition(): int
    {
        return $this->position;
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
            'position' => $this->position,
            'ticket_id' => $this->ticket_id,
            'sections' => $this->sections
        ];
    }
}