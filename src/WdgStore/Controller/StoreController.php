<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StoreController extends AbstractActionController
{
    protected $service;
    
    public function indexAction()
    {
        $store_service      = $this->getStoreService();
        $featured_products  = $store_service->getFeaturedProductsPaginator(1, 5);
        
        return new ViewModel(array("featured_products" => $featured_products));
    }
    
    public function productAction()
    {
        $slug       = $this->params()->fromRoute('slug', '');
        $service    = $this->getStoreService();
        $product    = $service->getProductBySlug($slug);
        
        if(!$product)
        {
            $this->getResponse()->setStatusCode(404);
            return; 
        }
        
        return new ViewModel(array("product" => $product));
    }
    
    public function categoryAction()
    {
        $slug       = $this->params()->fromRoute('slug', '');
        $service    = $this->getStoreService();
        $category   = $service->getCategoryBySlug($slug);
        
        if(!$category)
        {
            $this->getResponse()->setStatusCode(404);
            return; 
        }
        
        return new ViewModel(array("category" => $category));
    }
    
    /**
     * @return \WdgStore\Service\Store
     */
    public function getStoreService()
    {
        if($this->service === null)
        {
            $this->service = $this->getServiceLocator()->get('wdgstore_service_store');
        }
        
        return $this->service;
    }
}