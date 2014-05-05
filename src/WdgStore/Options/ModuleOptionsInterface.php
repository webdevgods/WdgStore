<?php
namespace WdgStore\Options;

interface ModuleOptionsInterface
{
    public function setProductListElements(array $listElements);

    public function getProductListElements();
    
    public function setImageTag($thumbnailImageTag);
    
    /**
     * Filebank tag for product specific images
     */
    public function getImageTag();
}
