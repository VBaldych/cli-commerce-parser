<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'products:item']),
        new GetCollection(normalizationContext: ['groups' => 'products:list'])
    ],
    paginationEnabled: false,
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['products:list', 'products:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 512)]
    #[Groups(['products:list', 'products:item'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['products:list', 'products:item'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['products:list', 'products:item'])]
    private ?string $image_url = null;

    #[ORM\Column(length: 255)]
    #[Groups(['products:list', 'products:item'])]
    private ?string $product_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getProductUrl(): ?string
    {
        return $this->product_url;
    }

    public function setProductUrl(string $product_url): static
    {
        $this->product_url = $product_url;

        return $this;
    }
}
