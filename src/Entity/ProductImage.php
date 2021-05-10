<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductImageRepository; 

/**
 * @ORM\Entity(repositoryClass=ProductImageRepository::class) 
 */
class ProductImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_size;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="productImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getImageSize(): ?string
    {
        return $this->image_size;
    }

    public function setImageSize(string $image_size): self
    {
        $this->image_size = $image_size;

        return $this;
    }

    public function getProduct(): ?Produit
    {
        return $this->product;
    }

    public function setProduct(?Produit $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getImageFile():?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile; 
        if(null != $this->imageFile){
            $this->updated_at = new \DateTime('now');
        } 
    }

 
}
