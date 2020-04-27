<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="property_photo", fileNameProperty="photo_name", size="photo_size", originalName="photo_original_name")
     *
     */
    private $photo_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_original_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $photo_size;

    /**
     * @Gedmo\SortablePosition()
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;

    /**
     * @Gedmo\SortableGroup()
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="photos")
     * @ORM\JoinColumn("property_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $property;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoFile()
    {
        return $this->photo_file;
    }

    public function setPhotoFile(?File $photo_file): void
    {
        $this->photo_file = $photo_file;
        if ($photo_file) {
            $this->update_at = new \DateTime();
        }
    }

    public function getPhotoName(): ?string
    {
        return $this->photo_name;
    }

    public function setPhotoName(?string $photo_name): self
    {
        $this->photo_name = $photo_name;

        return $this;
    }

    public function getPhotoOriginalName(): ?string
    {
        return $this->photo_original_name;
    }

    public function setPhotoOriginalName(?string $photo_original_name): self
    {
        $this->photo_original_name = $photo_original_name;

        return $this;
    }

    public function getPhotoSize(): ?int
    {
        return $this->photo_size;
    }

    public function setPhotoSize(?int $photo_size): self
    {
        $this->photo_size = $photo_size;

        return $this;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

}
