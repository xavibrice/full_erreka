<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 * @Vich\Uploadable()
 */
class Property
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $portal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $floor;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="properties")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", inversedBy="properties")
     */
    private $owner;

    /**
     * @ORM\Column(type="date")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="property", cascade={"persist"})
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Street", inversedBy="properties")
     */
    private $street;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPortal(): ?string
    {
        return $this->portal;
    }

    public function setPortal(?string $portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(?string $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getOwner()->getFullName();
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
            $photo->setProperty($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProperty() === $this) {
                $photo->setProperty(null);
            }
        }

        return $this;
    }

    public function getStreet(): ?Street
    {
        return $this->street;
    }

    public function setStreet(?Street $street): self
    {
        $this->street = $street;

        return $this;
    }
}
