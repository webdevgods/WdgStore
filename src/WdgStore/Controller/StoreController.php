<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StoreController extends AbstractActionController
{
    public function indexAction()
    {
        /* @var $store_service \WdgStore\Service\Store */
        $store_service   = $this->getServiceLocator()->get('wdgstore_service_store');
        $featured_products = $store_service->getFeaturedProductsPaginator(1, 5);
        
        return new ViewModel(array("featured_products" => $featured_products));
    }
}