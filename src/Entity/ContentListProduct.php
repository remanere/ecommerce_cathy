<?php

namespace App\Entity;

use App\Repository\ContentListProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentListProductRepository::class)]
class ContentListProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ListProduct::class, inversedBy: 'contentListProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private $ListProduct;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListProduct(): ?ListProduct
    {
        return $this->ListProduct;
    }

    public function setListProduct(?ListProduct $ListProduct): self
    {
        $this->ListProduct = $ListProduct;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
