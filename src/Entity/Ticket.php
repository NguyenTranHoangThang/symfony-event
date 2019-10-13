<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *     message = "Ticket's name should not be blank."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(
     *     message = "Ticket's description should not be blank."
     * )
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $event_id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @Assert\NotBlank(
     *     message = "Ticket's start date should not be blank."
     * )
     * @ORM\Column(type="datetime")
     */
    private $start_at;

    /**
     * @Assert\NotBlank(
     *     message = "Ticket's end date tshould not be blank."
     * )
     * @ORM\Column(type="datetime")
     */
    private $end_at;

    /**
     * @Assert\NotBlank(
     *     message = "Number of ticket(s) should not be blank."
     * )
     * @Assert\Positive(
     *     message = "Ticket's price should be positive"
     * )
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\NotBlank(
     *     message = "Ticket's price should not be blank."
     * )
     * @Assert\Positive(
     *     message = "Ticket's price should be positive"
     * )
     * @ORM\Column(type="integer")
     */
    private $nums_of_ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function setEventId(int $event_id): self
    {
        $this->event_id = $event_id;

        return $this;
    }


    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNumsOfTicket(): ?int
    {
        return $this->nums_of_ticket;
    }

    public function setNumsOfTicket(int $nums_of_ticket): self
    {
        $this->nums_of_ticket = $nums_of_ticket;

        return $this;
    }
}
