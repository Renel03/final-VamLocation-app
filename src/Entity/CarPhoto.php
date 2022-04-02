<?php

namespace App\Entity;

use App\Repository\CarPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CarPhotoRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class CarPhoto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @Assert\Image(
     *  maxSize="1M",
     *  minWidth=345,
     *  maxWidth=640,
     *  minHeight=345
     * )
     */
    private $temp;
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    public function getId(): ?int
    {
        return $this->id;
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
        if (!is_null($this->src)) {
            $this->temp = $this->src;
            $this->src = null;
        }

        if (!is_null($this->file)) {
            $this->src = md5(uniqid()) . '.' . $this->file->guessExtension();
        }

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

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
            $this->getUploadRootDir(),$this->src
        );
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->temp = $this->src;
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
        return 'uplds/cars/photos';
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
        return $this->getUploadDir().'/'.$this->getSrc();
    }
}
