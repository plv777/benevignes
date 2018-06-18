<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequestOfferRepository")
 */
class RequestOffer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $iduser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $idOffer;

    public function getId()
    {
        return $this->id;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIdOffer(): ?int
    {
        return $this->idOffer;
    }

    public function setIdOffer(int $idOffer): self
    {
        $this->idOffer = $idOffer;

        return $this;
    }
}
