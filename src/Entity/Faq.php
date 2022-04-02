<?php

namespace App\Entity;

use App\Repository\FaqRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FaqRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Faq
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Groups({"list", "details"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list", "details"})
     */
    private $content;

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
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * @ORM\PrePersist()
     */
    public function prePersist(){
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(){
        $this->updatedAt = new \DateTime();
    }
}
