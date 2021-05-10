<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;


class SearchProduit {

    /**
     * @var int|null
     */
    private $minPrice;

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var ArrayCollection
     */
    private $categories;

    public function __construct(){
        $this->categories = new ArrayCollection();
    } 
    /**
     * @var ArrayCollection
     */
    public function getCategories(): ArrayCollection{
        return $this->categories;
    }

    public function getMinPrice(){
        return $this->minPrice;
    }

    public function setMinPrice(int $minPrice): self{
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(){
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): self{
        $this->maxPrice = $maxPrice;

        return $this;
    }
    
}