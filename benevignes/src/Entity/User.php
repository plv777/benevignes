<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=320)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $activeToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="owner", orphanRemoval=true)
     */
    private $offers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RequestOffer", mappedBy="author", orphanRemoval=true)
     */
    private $requestOffers;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $wineArea;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $firstName;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->requestOffers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getActiveToken(): ?string
    {
        return $this->activeToken;
    }

    public function setActiveToken(string $activeToken): self
    {
        $this->activeToken = $activeToken;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setOwner($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getOwner() === $this) {
                $offer->setOwner(null);
            }
        }

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
            $requestOffer->setAuthor($this);
        }

        return $this;
    }

    public function removeRequestOffer(RequestOffer $requestOffer): self
    {
        if ($this->requestOffers->contains($requestOffer)) {
            $this->requestOffers->removeElement($requestOffer);
            // set the owning side to null (unless already changed)
            if ($requestOffer->getAuthor() === $this) {
                $requestOffer->setAuthor(null);
            }
        }

        return $this;
    }

    public function getWineArea(): ?string
    {
        return $this->wineArea;
    }

    public function setWineArea(?string $wineArea): self
    {
        $this->wineArea = $wineArea;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }
}