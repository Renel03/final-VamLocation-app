<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Serializable;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"cp"}, message="Désolé, il existe déjà une ville avec ce code postal.")
 */
class City implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details", "hide"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"list", "details", "hide"})
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"cp","name"})
     * @ORM\Column(length=100, unique=true)
     * @Groups({"list", "details", "hide"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @Groups({"list", "details", "hide"})
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"list", "details", "hide"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"list", "details", "hide"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list", "details", "hide"})
     */
    private $description;

    /**
     * @Assert\Image(
     *  maxSize="1M",
     *  minWidth=345,
     *  maxWidth=640,
     *  minHeight=345
     * )
     */
    private $file;
    private $temp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

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

    public function getTemp(): ?string
    {
        return $this->temp;
    }

    public function setTemp(string $temp): self
    {
        $this->temp = $temp;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        if (!is_null($this->photo)) {
            $this->temp = $this->photo;
            $this->photo = null;
        }

        if (!is_null($this->file)) {
            $this->photo = md5(uniqid()) . '.' . $this->file->guessExtension();
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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
            $this->getUploadRootDir(),$this->photo
        );
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->temp = $this->photo;
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
        return 'uplds/cities';
    }
    
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../public/' . $this->getUploadDir();
    }
    
    /**
     * @Groups({"list", "details", "hide"})
     */
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getPhoto();
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->cp
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name,
            $this->cp
        ) = unserialize($serialized,['allowed_classes'=>false]);
    }
}
