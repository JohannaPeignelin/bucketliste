<?php

namespace App\Entity;

use App\Repository\WishRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message: "A title is mandatory")]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: "Minimum {{ limit }} character !",
        maxMessage: "Maximum {{ limit }} character ! "

    )]
    #[ORM\Column(length: 250)]
    private ?string $title = null;

    #[Assert\Length(
        min: 10,
        max: 4000,
        minMessage: "Minimum {{ limit }} character please!",
        maxMessage: "Maximum {{ limit }} characters please !"
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Assert\NotBlank(message: "An author is mandatory")]
    #[ORM\Column(length: 50)]
    private ?string $author = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateCreated = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    #[ORM\PrePersist]
    public function setNewWish(){
        $this->setDateCreated(new \DateTime());
        $this->setIsPublished(true);

    }
}
