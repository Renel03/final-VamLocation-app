<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
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
     * @ORM\ManyToOne(targetEntity=Demand::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demand;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Response::class, mappedBy="offer", orphanRemoval=true)
     */
    private $responses;

    /**
     * @ORM\ManyToMany(targetEntity=Car::class)
     */
    private $cars;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemand(): ?Demand
    {
        return $this->demand;
    }

    public function setDemand(?Demand $demand): self
    {
        $this->demand = $demand;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setOffer($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getOffer() === $this) {
                $response->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
        }

        return $this;
    }
}
