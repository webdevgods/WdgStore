<?php
namespace WdgStore\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    WdgStore\Options\ModuleOptions;

class StoreAdminProductController extends AbstractActionController
{
    protected $options;
    private $storeService;
    
    public function listAction()
    {        
        $page       = (int) $this->params()->fromRoute('page', 0);
        $paginator  = $this->getStoreService()->getProductsAlphaPaginator($page, 10);
        
        if($paginator->count() >0 && $paginator->count() < $page)
            $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/list");
        
        return new ViewModel(array(
            'products' => $paginator,
            'productlistElements' => $this->getOptions()->getProductListElements()
        ));
    }
    
    public function addAction()
    {        
        $service    = $this->getStoreService();
        $form       = $service->getAddProductForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Product = $service->addProductByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Added Product");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $Product->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not add product: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function editAction()
    {
        $service    = $this->getStoreService();
        $form       = $service->getEditProductForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $data = array_merge_recursive(
                    $post->toArray(),          
                    $this->getRequest()->getFiles()->toArray()
                );
                
                $Product = $service->EditProductByArray($data);
                
                $this->flashMessenger()->addSuccessMessage("Edited Product");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $Product->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not edit product: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function showAction()
    {        
        $id         = (int) $this->params()->fromRoute('id', 0);
        $product    = $this->getStoreService()->getProductById($id);
        
        if(!$product)
        {
            $this->flashMessenger()
                ->addErrorMessage(
                    $this->getTranslator()->translate('No product with that id')
                );
            
            $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/list");
        }
        
        return new ViewModel(array("product" => $this->getStoreService()->getProductById($id)));
    }
    
    public function imageAddAction()
    {
        $id         = (int) $this->params()->fromRoute('id', 0);
        $service    = $this->getStoreService();
        $product    = $service->getProductById($id);
        $request    = $this->getRequest();
        
        if(!$product)
        {
            $this->flashMessenger()
                ->addErrorMessage(
                    $this->getTranslator()->translate('No product with that id')
                );
            
            $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/list");
        }
        
        $form = $service->getProductAddImageForm($product);
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $data = array_merge_recursive(
                    $post->toArray(),          
                    $this->getRequest()->getFiles()->toArray()
                );
                
                $image = $service->addProductImageByArray($data);
                
                $this->flashMessenger()->addSuccessMessage("Added Image");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $product->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not add image: ".$exc->getMessage());
            }
            
            $form->setData($data)->isValid();   
        }
        
        return new ViewModel(array("form" => $form, "product" => $product));
    }
    
    public function imageFeaturedAction()
    {        
        $id         = (int) $this->params()->fromRoute('id', 0);
        $image_id   = (int) $this->params()->fromRoute('image_id', 0);
       
        try 
        {
            $this->getStoreService()->setImageFeatured($id, $image_id);
        }
        catch (\Exception $exc)
        {
            $this->flashMessenger()->addErrorMessage("Could not set image to featured: ".$exc->getMessage());
        }
        
        return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $id));
    }
    
    public function imageRemoveAction()
    {        
        $id         = (int) $this->params()->fromRoute('id', 0);
        $image_id   = (int) $this->params()->fromRoute('image_id', 0);
       
        try 
        {
            $this->getStoreService()->setImageFeatured($id, $image_id);
        }
        catch (\Exception $exc)
        {
            $this->flashMessenger()->addErrorMessage("Could not set image to featured: ".$exc->getMessage());
        }
        
        return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $id));
    }
    
    public function categoriesAction()
    {        
        $service    = $this->getStoreService();
        $id         = $this->getEvent()->getRouteMatch()->getParam("id");
        $form       = $service->getProductCategoryForm($id);
        $request    = $this->getRequest();
        $Product       = $service->getProductById($id);
                
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Product = $service->editProductCategoriesByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Product Categories Saved");

                return $this->redirect()->toRoute("zfcadmin/wdg-store-admin/product/show", array("id" => $Product->getId()));
            }
            catch (\WdgStore\Exception\Service\Store\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not manage product categories: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form, "product" => $Product));
    }
    
    /**
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
}