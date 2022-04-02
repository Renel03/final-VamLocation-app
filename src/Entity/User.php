<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Serializable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Désolé, cette adresse e-mail est déjà utilisée par un autre compte")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "details", "hide"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"list", "details"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     * @Groups({"list", "details"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list", "details"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"list", "details"})
     * @Assert\Regex(
     *  pattern="/^(\+)?[0-9]{7,}$/",
     *  message="Le numéro de téléphone n'est pas valide"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"list", "details", "hide"})
     * @Assert\Length(min=2)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"list", "details", "hide"})
     * @Assert\Length(min=2)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"list", "details"})
     * @Assert\Length(min=2)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"details"})
     * @Assert\Length(min=3)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"details"})
     * @Assert\Regex(
     *  pattern="/^[0-9 ]{2,}$/",
     *  message="Le n° nif n'est pas valide"
     * )
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"details"})
     * @Assert\Regex(
     *  pattern="/^[0-9 ]{2,}$/",
     *  message="Le n° stat n'est pas valide"
     * )
     */
    private $stat;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"details"})
     * @Assert\Regex(
     *  pattern="/^[0-9a-z ]{2,}$/i",
     *  message="Le n° stat n'est pas valide"
     * )
     */
    private $rcs;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private $isIdentityVerified = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private $isNifVerified = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private $isStatVerified = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list", "details"})
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"list", "details"})
     */
    private $businessType;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"details"})
     */
    private $about;

    /**
     * @ORM\ManyToOne(targetEntity=City::class)
     * @Groups({"list", "details"})
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Demand::class, mappedBy="user", orphanRemoval=true)
     */
    private $demands;

    /**
     * @ORM\ManyToMany(targetEntity=Conversation::class, mappedBy="users")
     */
    private $conversations;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, cascade={"persist", "remove"})
     * @Groups({"details"})
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity=SubscripHistory::class, mappedBy="user", orphanRemoval=true)
     */
    private $subscripHistories;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list", "details"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpiredAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"details"})
     */
    private $isRcsVerified = false;

    public function __construct()
    {
        $this->demands = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->subscripHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(?string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }

    public function getStat(): ?string
    {
        return $this->stat;
    }

    public function setStat(?string $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(?string $rcs): self
    {
        $this->rcs = $rcs;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsIdentityVerified(): ?bool
    {
        return $this->isIdentityVerified;
    }

    public function setIsIdentityVerified(bool $isIdentityVerified): self
    {
        $this->isIdentityVerified = $isIdentityVerified;

        return $this;
    }

    public function getIsNifVerified(): ?bool
    {
        return $this->isNifVerified;
    }

    public function setIsNifVerified(bool $isNifVerified): self
    {
        $this->isNifVerified = $isNifVerified;

        return $this;
    }

    public function getIsStatVerified(): ?bool
    {
        return $this->isStatVerified;
    }

    public function setIsStatVerified(bool $isStatVerified): self
    {
        $this->isStatVerified = $isStatVerified;

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

    public function getBusinessType(): ?int
    {
        return $this->businessType;
    }

    public function setBusinessType(int $businessType): self
    {
        $this->businessType = $businessType;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Demand[]
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(Demand $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands[] = $demand;
            $demand->setUser($this);
        }

        return $this;
    }

    public function removeDemand(Demand $demand): self
    {
        if ($this->demands->contains($demand)) {
            $this->demands->removeElement($demand);
            // set the owning side to null (unless already changed)
            if ($demand->getUser() === $this) {
                $demand->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            $conversation->removeUser($this);
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Collection|SubscripHistory[]
     */
    public function getSubscripHistories(): Collection
    {
        return $this->subscripHistories;
    }

    public function addSubscripHistory(SubscripHistory $subscripHistory): self
    {
        if (!$this->subscripHistories->contains($subscripHistory)) {
            $this->subscripHistories[] = $subscripHistory;
            $subscripHistory->setUser($this);
        }

        return $this;
    }

    public function removeSubscripHistory(SubscripHistory $subscripHistory): self
    {
        if ($this->subscripHistories->contains($subscripHistory)) {
            $this->subscripHistories->removeElement($subscripHistory);
            // set the owning side to null (unless already changed)
            if ($subscripHistory->getUser() === $this) {
                $subscripHistory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->isActive
        ) = unserialize($serialized,['allowed_classes'=>false]);
    }

    /**
     * @Assert\IsTrue(message="Veuillez saisir le nom de l'entreprise")
     */
    public function isCampanyName()
    {
        if($this->type == 1)
            return !is_null($this->companyName);
        return true;
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

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpiredAt(): ?\DateTimeInterface
    {
        return $this->tokenExpiredAt;
    }

    public function setTokenExpiredAt(?\DateTimeInterface $tokenExpiredAt): self
    {
        $this->tokenExpiredAt = $tokenExpiredAt;

        return $this;
    }

    public function getIsRcsVerified(): ?bool
    {
        return $this->isRcsVerified;
    }

    public function setIsRcsVerified(bool $isRcsVerified): self
    {
        $this->isRcsVerified = $isRcsVerified;

        return $this;
    }
}
