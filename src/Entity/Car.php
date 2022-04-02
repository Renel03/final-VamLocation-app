<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Trademark::class)
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
     * @ORM\Column(type="string", length=10)
     * @Groups({"list", "details"})
     */
    private $num;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list", "details"})
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details"})
     */
    private $nbPlace;

    /**
     * @Assert\File(
     *  maxSize="1M"
     * )
     */
    private $file;
    private $temp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enclosure;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=CarPhoto::class, mappedBy="car", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid()
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
     * @Groups({"list", "details"})
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

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

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

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        if (!is_null($this->enclosure)) {
            $this->temp = $this->enclosure;
            $this->enclosure = null;
        }

        if (!is_null($this->file)) {
            $this->enclosure = md5(uniqid()) . '.' . $this->file->guessExtension();
        }

        return $this;
    }

    public function getTemp(): ?string
    {
        return $this->temp;
    }

    public function setTemp(string $temp): self
    {
        $this->temp = $temp;

        return $this;
    }

    public function getEnclosure(): ?string
    {
        return $this->enclosure;
    }

    public function setEnclosure(?string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|CarPhoto[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(CarPhoto $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setCar($this);
        }

        return $this;
    }

    public function removePhoto(CarPhoto $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getCar() === $this) {
                $photo->setCar(null);
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

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (is_null($this->file)) {
            return;
        }
        
        if (!is_null($this->temp)) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->temp;
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),$this->enclosure
        );
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->temp = $this->enclosure;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $temp = $this->getUploadRootDir() . '/' . $this->temp;
        if (file_exists($temp)) {
            @unlink($temp);
        }
    }
    
    public function getUploadDir()
    {
        return 'uplds/folders';
    }
    
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../public/' . $this->getUploadDir();
    }
    
    /**
     * @Groups({"list", "details"})
     */
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getEnclosure();
    }
}
