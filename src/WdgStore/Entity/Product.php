<?php
namespace WdgStore\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    WdgDoctrine2\Entity\Entity;

/**
 * @ORM\Table(
 *      name                = "wdgstore_products",
 *      uniqueConstraints   = {
 *          @ORM\UniqueConstraint(name="slug_idx", columns={"slug"})
 *      },
 *      indexes = {
 *          @ORM\Index(name="title_idx",        columns={"title"}),
 *          @ORM\Index(name="creationDate_idx", columns={"created"}),
 *      }
 * )
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
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $thumbnail = "";

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $thumbnail_alt = "";

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
     * @param string $excerpt
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = (int) $price;
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
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Product
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = (string)$thumbnail;
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getThumbnailHtml($attributes = "style='width:100px'")
    {
        return "<img src='".htmlspecialchars($this->getThumbnail()).
        "' $attributes alt='".htmlspecialchars($this->getThumbnailAlt())."' ".
        " title='".htmlspecialchars($this->getThumbnailAlt())."' />";
    }

    /**
     * @param string $thumbnail_alt
     * @return Product
     */
    public function setThumbnailAlt($thumbnail_alt)
    {
        $this->thumbnail_alt = (string) $thumbnail_alt;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnailAlt()
    {
        return $this->thumbnail_alt;
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