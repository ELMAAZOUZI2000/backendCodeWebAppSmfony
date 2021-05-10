<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;  
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)  
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("product:read")
     */
    private $id;
   
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10,max=255, minMessage="Votre message est bien court")  
     * @Groups("product:read")
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=255)
     * @Assert\Length(min=7, max=255) 
     * @Groups("product:read")
     */
    private $description;
  
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     * @Groups("product:read")
     */
    private $reference;

    /**
     * @ORM\Column(type="float")
     * @Groups("product:read")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("product:read")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product", cascade={"persist"})
     */
    private $productImages; 
 

    public function __construct(){ 
        $productImages = new ArrayCollection();
    }
    
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function formattedPrice(){
        return  number_format($this->prix, 0,'',' ');
    }
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    } 
 
    /**
     * @return Collection|ProductImage[]
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {     
        //if (!$this->productImages->contains($productImage)) { 
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        //}

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

}
