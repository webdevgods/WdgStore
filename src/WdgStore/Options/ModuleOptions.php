<?php
namespace WdgStore\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * Array of data to show in the product list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    protected $productListElements = array('Name' => 'name', 'Slug' => 'slug');
    
    /**
     * Array of data to show in the category list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    protected $categoryListElements = array('Name' => 'name', 'Slug' => 'slug');
    
    /**
     * @var Filebank tag for product thumbnail specific images
     */
    protected $imageTag = "";

    public function setProductListElements(array $listElements)
    {
        $this->productListElements = $listElements;
    }

    public function getProductListElements()
    {
        return $this->productListElements;
    }
    
    public function setCategoryListElements(array $listElements)
    {
        $this->categoryListElements = $listElements;
    }

    public function getCategoryListElements()
    {
        return $this->categoryListElements;
    }
    
    /**
     * This is the name of the tag to put in the filebank for all of the
     * images so they can be filtered later
     * 
     * @param string $imageTag
     */
    public function setImageTag($imageTag)
    {
        $this->imageTag = $imageTag;
    }
    
    /**
     * Filebank tag for product specific images
     * 
     * @return type
     */
    public function getImageTag()
    {
        return $this->imageTag;
    }
}
