<?php

namespace App\Entity;

use App\Repository\VersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VersionRepository::class)
 * @UniqueEntity(fields={"model", "name"}, message="Désolé, cette version existe déjà dans cette modèle.")
 */
class Version
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"list", "details"})
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=20, unique=true)
     * @Groups({"list", "details"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "details"})
     */
    private $model;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $status = false;

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

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

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
}
