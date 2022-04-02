<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Trademark::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "details"})
     */
    private $trademark;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class)
     * @Groups({"list", "details"})
     */
    private $model;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     * @Groups({"list", "details"})
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"details"})
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details"})
     */
    private $state = 0;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"list", "details"})
     */
    private $fuel;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * @Groups({"list", "details"})
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"list", "details"})
     */
    private $mileage;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Groups({"list", "details"})
     */
    private $consummation;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     * @Groups({"list", "details"})
     */
    private $power;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"list", "details"})
     */
    private $nbDoor;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"list", "details"})
     */
    private $nbPlace;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"list", "details"})
     */
    private $speedType;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"list", "details"})
     */
    private $carType;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"list", "details"})
     */
    private $motoType;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list", "details"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list", "details"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="post", orphanRemoval=true)
     * @Assert\Valid()
     * @Assert\Count(
     *  min=1,
     *  minMessage="Vueillez ajouter au moins une photo s'il vous plait",
     *  max=5,
     *  maxMessage="Désolé, vous ne pouvez pas ajouter que {{ limit }} photos"
     * )
     * @Groups({"list", "details"})
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "details"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Version::class)
     */
    private $version;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTrademark(): ?Trademark
    {
        return $this->trademark;
    }

    public function setTrademark(?Trademark $trademark): self
    {
        $this->trademark = $trademark;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(?int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getConsummation(): ?string
    {
        return $this->consummation;
    }

    public function setConsummation(?string $consummation): self
    {
        $this->consummation = $consummation;

        return $this;
    }

    public function getPower(): ?string
    {
        return $this->power;
    }

    public function setPower(?string $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getNbDoor(): ?int
    {
        return $this->nbDoor;
    }

    public function setNbDoor(?int $nbDoor): self
    {
        $this->nbDoor = $nbDoor;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(?int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getSpeedType(): ?string
    {
        return $this->speedType;
    }

    public function setSpeedType(?string $speedType): self
    {
        $this->speedType = $speedType;

        return $this;
    }

    public function getCarType(): ?string
    {
        return $this->carType;
    }

    public function setCarType(?string $carType): self
    {
        $this->carType = $carType;

        return $this;
    }

    public function getMotoType(): ?string
    {
        return $this->motoType;
    }

    public function setMotoType(?string $motoType): self
    {
        $this->motoType = $motoType;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setPost($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getPost() === $this) {
                $photo->setPost(null);
            }
        }

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
     * @ORM\PrePersist()
     */
    public function prePersist(){
        $this->createdAt = new \DateTime();
        // $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(){
        $this->updatedAt = new \DateTime();
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
    }
}
