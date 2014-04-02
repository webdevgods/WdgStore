<?php
namespace WdgStore\Entity;

use Doctrine\ORM\Mapping as ORM,
    WdgDoctrine2\Entity\Entity,
    Doctrine\Common\Collections\ArrayCollection;
use WdgStore\Repository\Subscription;

/**
 * Category
 *
 * @ORM\Table(
 *      name="wdgstore_categories",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="name_idx", columns={"name"}),
 *          @ORM\UniqueConstraint(name="slug_idx", columns={"slug"})
 *      },
 *      indexes={
 *          @ORM\Index(name="creationDate_idx", columns={"created"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="WdgStore\Repository\Category")
 */
class Category extends Entity
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
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="Categories")
     */
    protected $Products;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Subscription", mappedBy="Categories")
     */
    protected $Subscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->Products = new ArrayCollection();
        $this->Subscriptions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->Products;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        if ($this->Products->contains($product)) return;

        $this->Products->add($product);

        $product->addCategory($this);
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        if (!$this->Products->contains($product)) return;

        $this->Products->removeElement($product);

        $product->removeCategory($this);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->Subscriptions;
    }

    /**
     * @param Subscription $subscription
     */
    public function addSubscription(Subscription $subscription)
    {
        if ($this->Subscriptions->contains($subscription)) return;

        $this->Subscriptions->add($subscription);

        $subscription->addCategory($this);
    }

    /**
     * @param Subscription $subscription
     */
    public function removeSubscription(Subscription $subscription)
    {
        if (!$this->Subscriptions->contains($subscription)) return;

        $this->Subscriptions->removeElement($subscription);

        $subscription->removeCategory($this);
    }

}