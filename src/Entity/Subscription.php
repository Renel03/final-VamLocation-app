<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "details"})
     */
    private $plan;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"details"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"details"})
     */
    private $expiredAt;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"details"})
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"details"})
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
