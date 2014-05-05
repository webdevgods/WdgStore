<?php
namespace WdgStore\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    WdgDoctrine2\Entity\Entity;

/**
 * @ORM\Table(name = "wdgstore_products")
 * @ORM\Entity(repositoryClass="WdgStore\Repository\Product")
 */
class Product extends Entity
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var integer
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $slug;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=1)
     */
    protected $featured = 0;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="FileBank\Entity\File")
     *  @ORM\JoinTable(name="wdgstore_product_images")
     */
    protected $images;
    
    /**
     * @var \FileBank\Entity\File
     * @ORM\ManyToOne(targetEntity="FileBank\Entity\File")
     */
    protected $featured_image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="Products")
     * @ORM\JoinTable(
     *  name="wdgstore_product_categories",
     *  joinColumns={
     *      @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $Categories;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->Categories   = new ArrayCollection();
        $this->images       = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = (float) $price;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = (string)$slug;
        
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
   
    /**
     * @param int $featured 0 or 1
     * @return \WdgStore\Entity\Product
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getFeatured()
    {
        return $this->featured;
    }
    
    /**
     * @return bool
     */
    public function isFeatured()
    {
        return $this->getFeatured() === 1 ? true : false;
    }
    
    /**
     * @return string
     */
    public function isFeaturedString()
    {
        return $this->isFeatured() ? "yes" : "no";
    }
    
    /**
     * @param \FileBank\Entity\File $image
     * @return \WdgStore\Entity\Product
     */
    public function addImage(\FileBank\Entity\File $image)
    {
        $this->images->add($image);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * @param \FileBank\Entity\File $image
     * @return \WdgStore\Entity\Product
     */
    public function removeImage(\FileBank\Entity\File $image)
    {
        if (!$this->images->contains($image)) return;

        $this->images->removeElement($image);
        
        return $this;
    }
    
    /**
     * @param \FileBank\Entity\File $image
     * @return \WdgStore\Entity\Product
     */
    public function setFeaturedImage(\FileBank\Entity\File $image)
    {
        if(!$this->getImages()->contains($image))
        {
            $this->addImage($image);
        }
        
        $this->featured_image = $image;
        
        return $this;
    }
    
    /**
     * @return \FileBank\Entity\File
     */
    public function getFeaturedImage()
    {
        return $this->featured_image;
    }
    
    /**
     * @param \FileBank\Entity\File $file
     * @return boolean
     */
    public function isImageFeatured(\FileBank\Entity\File $file)
    {
        if($this->getFeaturedImage() && $this->getFeaturedImage()->getId() === $file->getId())
            return true;
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->getImages()->count() > 0;
    }
    
    /**
     * 
     * @return null|\FileBank\Entity\File
     */
    public function getFeaturedOrFirstImage()
    {
        if($this->getFeaturedImage())
            return $this->getFeaturedImage();
        
        if($this->hasImages())
            return $this->getImages()->offsetGet(0);
        
        return null;
    }

    /**
     * Add categories
     *
     * @param \Doctrine\Common\Collections\Collection|\WdgStore\Entity\Category $categories
     *
     * @return Product
     */
    public function addCategories(Collection $categories)
    {
        foreach ($categories as $category)
        {
            $this->addCategory($category);
        }

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Doctrine\Common\Collections\Collection|\WdgStore\Entity\Category $categories
     * @return \WdgStore\Entity\Product
     */
    public function removeCategories(Collection $categories)
    {
        foreach ($categories as $category)
        {
            $this->removeCategory($category);
        }

        return $this;
    }

    /**
     * @param \WdgStore\Entity\Category $category
     */
    public function addCategory(Category $category)
    {
        if ($this->Categories->contains($category))return;

        $this->Categories->add($category);

        $category->addProduct($this);
    }

    /**
     * @param \WdgStore\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        if (!$this->Categories->contains($category)) return;

        $this->Categories->removeElement($category);

        $category->removeProduct($this);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->Categories;
    }

}