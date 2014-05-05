<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel,
    WdgStore\Options\ModuleOptions;

class StoreAdminCategoryController extends AbstractActionController
{
    protected $options;
    protected $storeService;
    
    public function listAction()
    {
        $page       = (int) $this->params()->fromRoute('page', 0);
        $paginator  = $this->getStoreService()->getCategoriesPaginator($page, 10);
        
        if($paginator->count() > 0 && $paginator->count() < $page)
            $this->redirect()->toRoute("zfcadmin/wdg-store-admin/category/list");
        
        return new ViewModel(array(
            'categories' => $paginator,
            'categorylistElements' => $this->getOptions()->getCategoryListElements()
        ));
    }
    
    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        return new ViewModel(array("category" => $this->getStoreService()->getCategoryById($id)));
    }
    
    public function addAction()
    {
        $service    = $this->getStoreService();
        $form       = $service->getAddCategoryForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Category = $service->addCategoryByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Added Category");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/category/show", array("id" => $Category->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not add category: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function editAction()
    {
        $service    = $this->getStoreService();
        $form       = $service->getEditCategoryForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Category = $service->EditCategoryByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Edited Category");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/category/show", array("id" => $Category->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not edit category: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function deleteAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam("id");
        
        try 
        {
            $this->getStoreService()->deleteCategory($id);
            
            $this->flashMessenger()->addSuccessMessage("Category Deleted");
        } 
        catch(\Exception $exc) 
        {
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
        }
        
        return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/category/list");
    }
    
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('wdgstore_module_options'));
        }
        return $this->options;
    }
    
    /**
     * getStoreService
     *
     * @return \WdgStore\Service\Store
     */
    public function getStoreService()
    {
        if (null === $this->storeService)
        {
            $this->storeService = $this->getServiceLocator()->get('wdgstore_service_store');
        }
        return $this->storeService;
    }
}