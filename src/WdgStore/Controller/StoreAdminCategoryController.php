<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StoreAdminCategoryController extends AbstractActionController
{
    public function indexAction()
    {

        return new ViewModel();
    }
}