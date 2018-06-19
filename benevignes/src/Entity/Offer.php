<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RequestOffer", mappedBy="offer", orphanRemoval=true)
     */
    private $requestOffers;

    public function __construct()
    {
        $this->requestOffers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|RequestOffer[]
     */
    public function getRequestOffers(): Collection
    {
        return $this->requestOffers;
    }

    public function addRequestOffer(RequestOffer $requestOffer): self
    {
        if (!$this->requestOffers->contains($requestOffer)) {
            $this->requestOffers[] = $requestOffer;
            $requestOffer->setOffer($this);
        }

        return $this;
    }

    public function removeRequestOffer(RequestOffer $requestOffer): self
    {
        if ($this->requestOffers->contains($requestOffer)) {
            $this->requestOffers->removeElement($requestOffer);
            // set the owning side to null (unless already changed)
            if ($requestOffer->getOffer() === $this) {
                $requestOffer->setOffer(null);
            }
        }

        return $this;
    }
}
