<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ModelRepository::class)
 * @UniqueEntity(fields={"name", "trademark"}, message="Désolé, cette modèle existe déjà dans cette marque.")
 */
class Model
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Trademark::class, inversedBy="models")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list", "details"})
     */
    private $trademark;

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
     * @ORM\OneToMany(targetEntity=Version::class, mappedBy="model", orphanRemoval=true)
     */
    private $versions;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $status = false;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
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

    /**
     * @return Collection|Version[]
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions[] = $version;
            $version->setModel($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        if ($this->versions->contains($version)) {
            $this->versions->removeElement($version);
            // set the owning side to null (unless already changed)
            if ($version->getModel() === $this) {
                $version->setModel(null);
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
}
