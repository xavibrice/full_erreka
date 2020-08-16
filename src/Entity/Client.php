<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="clients")
     */
    private $agent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyNote", mappedBy="client")
     */
    private $propertyNotes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visit", mappedBy="client")
     */
    private $visits;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationality;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropertyType", inversedBy="clients")
     */
    private $property_type;

    public function __construct()
    {
        $this->propertyNotes = new ArrayCollection();
        $this->visits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
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

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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
            $propertyNote->setClient($this);
        }

        return $this;
    }

    public function removePropertyNote(PropertyNote $propertyNote): self
    {
        if ($this->propertyNotes->contains($propertyNote)) {
            $this->propertyNotes->removeElement($propertyNote);
            // set the owning side to null (unless already changed)
            if ($propertyNote->getClient() === $this) {
                $propertyNote->setClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->full_name;
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
            $visit->setClient($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->contains($visit)) {
            $this->visits->removeElement($visit);
            // set the owning side to null (unless already changed)
            if ($visit->getClient() === $this) {
                $visit->setClient(null);
            }
        }

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

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

}
