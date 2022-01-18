<?php

namespace App\Entity\Shop;

use App\Repository\Shop\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`products`')]
class Product
{
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $status;

    /**
     * @var ArrayCollection|Type[]
     */
    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_types',
        joinColumns: [new ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')])]
    private $types;

    public function __construct(string $name, float $price, string $description = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->status = self::STATUS_ACTIVE;
    }

    public static function add(string $name, float $price, string $description = null): self
    {
        return new self($name, $price, $description);
    }

    public function edit(?string $name, ?float $price, ?string $description): void
    {
        $this->name = $name ?? $this->name;
        $this->price = $price ?? $this->price;
        $this->description = $description ?? $this->description;
    }

    public function addType(Type $type)
    {
        if ($this->types->contains($type)) {
            throw new \DomainException('Department already exists.');
        }

        $this->types->add($type);
    }

    public function removeType(Type $type): self
    {
        $this->types->removeElement($type);
        return $this;
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }
}
