<?php

namespace App\Entity\Shop;

use App\Repository\Shop\TypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ORM\Table(name: '`types`')]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    /**
     * @var Product
     * @ORM\ManyToMany(targetEntity="App\Model\Work\Entity\Projects\Project\Project", inversedBy="departments")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'types')]
    private $products;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function add(string $name): self
    {
        return new self($name);
    }

    public function edit(string $name): void
    {
        $this->name = $name;
    }

    public function isNameEqual(string $name): bool
    {
        return $this->name === $name;
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
}
