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
    const SELL_OR_RENT = [
        0 => 'Venta',
        1 => 'Alquiler',
    ];

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

    /**
     * @ORM\Column(type="integer")
     */
    private $reason;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyType", inversedBy="properties")
     */
    private $property_type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyNote", mappedBy="property")
     */
    private $propertyNotes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visit", mappedBy="property")
     */
    private $visits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyDiscount", mappedBy="property")
     */
    private $propertyDiscounts;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->propertyNotes = new ArrayCollection();
        $this->visits = new ArrayCollection();
        $this->propertyDiscounts = new ArrayCollection();
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

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPropertyType(): ?PropertyType
    {
        return $this->property_type;
    }

    public function setPropertyType(?PropertyType $property_type): self
    {
        $this->property_type = $property_type;

        return $this;
    }

    /**
     * @return Collection|PropertyNote[]
     */
    public function getPropertyNotes(): Collection
    {
        return $this->propertyNotes;
    }

    public function addPropertyNote(PropertyNote $propertyNote): self
    {
        if (!$this->propertyNotes->contains($propertyNote)) {
            $this->propertyNotes[] = $propertyNote;
            $propertyNote->setProperty($this);
        }

        return $this;
    }

    public function removePropertyNote(PropertyNote $propertyNote): self
    {
        if ($this->propertyNotes->contains($propertyNote)) {
            $this->propertyNotes->removeElement($propertyNote);
            // set the owning side to null (unless already changed)
            if ($propertyNote->getProperty() === $this) {
                $propertyNote->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits[] = $visit;
            $visit->setProperty($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->contains($visit)) {
            $this->visits->removeElement($visit);
            // set the owning side to null (unless already changed)
            if ($visit->getProperty() === $this) {
                $visit->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PropertyDiscount[]
     */
    public function getPropertyDiscounts(): Collection
    {
        return $this->propertyDiscounts;
    }

    public function addPropertyDiscount(PropertyDiscount $propertyDiscount): self
    {
        if (!$this->propertyDiscounts->contains($propertyDiscount)) {
            $this->propertyDiscounts[] = $propertyDiscount;
            $propertyDiscount->setProperty($this);
        }

        return $this;
    }

    public function removePropertyDiscount(PropertyDiscount $propertyDiscount): self
    {
        if ($this->propertyDiscounts->contains($propertyDiscount)) {
            $this->propertyDiscounts->removeElement($propertyDiscount);
            // set the owning side to null (unless already changed)
            if ($propertyDiscount->getProperty() === $this) {
                $propertyDiscount->setProperty(null);
            }
        }

        return $this;
    }
}
