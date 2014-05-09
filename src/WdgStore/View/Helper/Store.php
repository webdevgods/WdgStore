<?php
namespace WdgStore\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Store extends AbstractHelper
{
    /**
     * @var \WdgStore\Service\Store
     */
    protected $service;
    
    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->service->getAllCategories();
    }
    
    /**
     * @return \WdgStore\Service\Store
     */
    public function getStoreService()
    {
        return $this->service;
    }
    
    /**
     * @param \WdgStore\Service\Store $service
     * @return \WdgStore\View\Helper\Store
     */
    public function setStoreService( \WdgStore\Service\Store $service )
    {
        $this->service = $service;
        
        return $this;
    }
}