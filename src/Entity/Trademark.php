<?php

namespace App\Entity;

use App\Repository\TrademarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TrademarkRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"name", "type"}, message="Désolé, cette marque est déjà existe dans nos fichiers.")
 */
class Trademark
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
     * @ORM\Column(type="string", length=30)
     * @Groups({"list", "details"})
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=30, unique=true)
     * @Groups({"list", "details"})
     */
    private $slug;

    /**
     * @Assert\Image(
     *  maxSize="1M",
     *  minWidth=100,
     *  maxWidth=240,
     * )
     */
    private $file;
    private $temp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"details"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Model::class, mappedBy="trademark", orphanRemoval=true)
     */
    private $models;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="trademark", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $status = false;

    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->posts = new ArrayCollection();
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
        if (!is_null($this->logo)) {
            $this->temp = $this->logo;
            $this->logo = null;
        }

        if (!is_null($this->file)) {
            $this->logo = md5(uniqid()) . '.' . $this->file->guessExtension();
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setTrademark($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            // set the owning side to null (unless already changed)
            if ($model->getTrademark() === $this) {
                $model->setTrademark(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setTrademark($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getTrademark() === $this) {
                $post->setTrademark(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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
            $this->getUploadRootDir(),$this->logo
        );
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->temp = $this->logo;
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
        return 'uplds/logos';
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
        return $this->getUploadDir().'/'.$this->getLogo();
    }
}
