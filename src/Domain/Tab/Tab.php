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
    private $ticket_id;

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
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PUT') {
            try {
                v::stringVal()->assert($data['name']);
            } catch (NestedValidationException $ex) {
                throw new TabValidationException($request, 'Name must be a string.');
            }

            try {
                v::number()->assert($data['position']);
            } catch (NestedValidationException $ex) {
                throw new TabValidationException($request, 'Position must be a number.');
            }
        }

        if ($request->getMethod() == 'POST' || $request->getMethod() == 'PATCH') {
            try {
                v::number()->assert($data['ticket_id']);
            } catch (NestedValidationException $ex) {
                throw new TabValidationException($request, 'Ticket id must be a number.');
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
     * @param int $ticket_id
     */
    public function setTicketId(int $ticket_id): void
    {
        $this->ticket_id = $ticket_id;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return (int) $this->position;
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
        return (int) $this->ticket_id;
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
        if ($this->sections != null) {
            return [
                'id' => (int) $this->id,
                'name' => $this->name,
                'position' => (int) $this->position,
                'ticket_id' => (int) $this->ticket_id,
                'sections' => $this->sections
            ];
        } else {
            return [
                'id' => (int) $this->id,
                'name' => $this->name,
                'position' => (int) $this->position,
                'ticket_id' => (int) $this->ticket_id
            ];
        }
    }
}