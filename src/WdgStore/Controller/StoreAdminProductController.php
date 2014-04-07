<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StoreAdminProductController extends AbstractActionController
{
    public function indexAction()
    {

        return new ViewModel();
    }
}