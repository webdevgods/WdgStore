<?php
namespace WdgStore\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    WdgDoctrine2\Entity\Entity;

/**
 * @ORM\Table(name = "wdgstore_subscriptions")
 * @ORM\Entity(repositoryClass="WdgStore\Repository\Subscription")
 */
class Subscription extends Entity
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
     * Frequency in months.
     * 
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $frequency;

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
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="Subscriptions")
     * @ORM\JoinTable(
     *  name="wdgstore_subscription_categories",
     *  joinColumns={
     *      @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
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
     * @return Subscription
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
     * @return Subscription
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
     * @return Subscription
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
     * @param int $frequency
     * @return Subscription
     */
    public function setFrequency($frequency)
    {
        $this->price = (int) $frequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param string $slug
     * @return Subscription
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
     * @return Subscription
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
     * @return Subscription
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
     * @return Subscription
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
     * @return \WdgStore\Entity\Subscription
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

        $category->addSubscription($this);
    }

    /**
     * @param \WdgStore\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        if (!$this->Categories->contains($category)) return;

        $this->Categories->removeElement($category);

        $category->removeSubscription($this);
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