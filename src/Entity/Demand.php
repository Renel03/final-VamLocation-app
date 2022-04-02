<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DemandRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Demand
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details", "hide"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Groups({"list", "details", "hide"})
     */
    private $tripType;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @Groups({"list", "details", "hide"})
     */
    private $fromCity;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, cascade={"persist"})
     * @Assert\Valid()
     * @Groups({"list", "details", "hide"})
     */
    private $toCity;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\GreaterThan("now")
     * @Groups({"list", "details", "hide"})
     */
    private $checkInAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="checkInAt")
     * @Groups({"list", "details", "hide"})
     */
    private $checkOutAt;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     * @Groups({"list", "details", "hide"})
     */
    private $nbPers;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list", "details", "hide"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details", "hide"})
     */
    private $isWithDriver = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="demands", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Groups({"list", "details", "hide"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="demand", orphanRemoval=true)
     * @Groups({"details"})
     */
    private $offers;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details", "hide"})
     */
    private $status = 0;

    /**
     * @ORM\ManyToMany(targetEntity=City::class, cascade={"persist"})
     * @Groups({"list", "details", "hide"})
     */
    private $tripType3Cities;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list", "details", "hide"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Groups({"list", "details", "hide"})
     */
    private $luggage = 1;
    
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Groups({"list", "details", "hide"})
     */
    private $reason = 4;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->tripType3Cities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTripType(): ?int
    {
        return $this->tripType;
    }

    public function setTripType(int $tripType): self
    {
        $this->tripType = $tripType;

        return $this;
    }

    public function getFromCity(): ?City
    {
        return $this->fromCity;
    }

    public function setFromCity(?City $fromCity): self
    {
        $this->fromCity = $fromCity;

        return $this;
    }

    public function getToCity(): ?City
    {
        return $this->toCity;
    }

    public function setToCity(?City $toCity): self
    {
        $this->toCity = $toCity;

        return $this;
    }

    public function getNbPers(): ?int
    {
        return $this->nbPers;
    }

    public function setNbPers(int $nbPers): self
    {
        $this->nbPers = $nbPers;

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

    public function getIsWithDriver(): ?bool
    {
        return $this->isWithDriver;
    }

    public function setIsWithDriver(bool $isWithDriver): self
    {
        $this->isWithDriver = $isWithDriver;

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
            $offer->setDemand($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getDemand() === $this) {
                $offer->setDemand(null);
            }
        }

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

    /**
     * @return Collection|City[]
     */
    public function getTripType3Cities(): Collection
    {
        return $this->tripType3Cities;
    }

    public function addTripType3City(City $tripType3City): self
    {
        if (!$this->tripType3Cities->contains($tripType3City)) {
            $this->tripType3Cities[] = $tripType3City;
        }

        return $this;
    }

    public function removeTripType3City(City $tripType3City): self
    {
        if ($this->tripType3Cities->contains($tripType3City)) {
            $this->tripType3Cities->removeElement($tripType3City);
        }

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

    public function getLuggage(): ?int
    {
        return $this->luggage;
    }

    public function setLuggage(?int $luggage): self
    {
        $this->luggage = $luggage;

        return $this;
    }

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function getCheckInAt(): ?\DateTimeInterface
    {
        return $this->checkInAt;
    }

    public function setCheckInAt(\DateTimeInterface $checkInAt): self
    {
        $this->checkInAt = $checkInAt;

        return $this;
    }

    public function getCheckOutAt(): ?\DateTimeInterface
    {
        return $this->checkOutAt;
    }

    public function setCheckOutAt(?\DateTimeInterface $checkOutAt): self
    {
        $this->checkOutAt = $checkOutAt;

        return $this;
    }
}
