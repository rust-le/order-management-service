<?php

namespace App\Entity;

use App\Repository\ProductRatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRatingRepository::class)
 */
class ProductRating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $productRate;

    /**
     * @ORM\Column(type="integer")
     */
    private $productCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductRate(): ?float
    {
        return $this->productRate;
    }

    public function setProductRate(float $productRate): self
    {
        $this->productRate = $productRate;

        return $this;
    }

    public function getProductCount(): ?int
    {
        return $this->productCount;
    }

    public function setProductCount(int $productCount): self
    {
        $this->productCount = $productCount;

        return $this;
    }
}
