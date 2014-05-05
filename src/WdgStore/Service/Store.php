<?php
namespace WdgStore\Service;

use Zend\Form\Form,
    WdgZf2\Service\ServiceAbstract,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator as ZendPaginator,
    WdgStore\Entity\Product as ProductEntity,
    WdgStore\Entity\Category as CategoryEntity,
    WdgStore\Exception\Service\Store\FormException as FormException,
    WdgStore\Options\ModuleOptionsInterface;

class Store extends ServiceAbstract
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \WdgStore\Repository\Product
     */
    protected $productRepos;

    /**
     * @var \WdgStore\Repository\Category
     */
    protected $categoryRepos;
    
    /**
     * @var \WdgStore\Options\ModuleOptionsInterface
     */
    protected $options;

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getProductRepository()
    {
        if (null === $this->productRepos)
        {
            $this->productRepos = $this->getServiceManager()->get('wdgstore_repos_product');
        }

        return $this->productRepos;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository()
    {
        if (null === $this->categoryRepos)
        {
            $this->categoryRepos = $this->getServiceManager()->get('wdgstore_repos_category');
        }

        return $this->categoryRepos;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if($this->entityManager === null)
        {
            $this->entityManager = $this->getServiceManager()->get("wdgstore_doctrine_em");
        }

        return $this->entityManager;
    }
    
    /**
     * @param int $pageNumber
     * @param int $productsPerPage
     * @return ZendPaginator
     */
    public function getFeaturedProductsPaginator($pageNumber, $productsPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator($this->getProductRepository()
                                ->findByFeaturedProductsPaginationQuery())
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($productsPerPage);
    }
    
    /**
     * @param int $pageNumber
     * @param int $productsPerPage
     * @return ZendPaginator
     */
    public function getProductsAlphaPaginator($pageNumber, $productsPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator($this->getProductRepository()
                                ->findAllPaginationQuery())
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($productsPerPage);
    }

    /**
     * @param string $slug
     * @return ProductEntity
     */
    public function getProductBySlug($slug)
    {
        return $this->getProductRepository()->findOneBy(array("slug" => $slug));
    }

    /**
     * @param $slug
     * @return CategoryEntity
     */
    public function getCategoryBySlug($slug)
    {
        return $this->getCategoryRepository()->findOneBy(array("slug" => $slug));
    }

    /**
     * @param int $id
     * @return ProductEntity
     */
    public function getProductById($id)
    {
        return $this->getProductRepository()->findOneBy(array("id" => $id));
    }

    /**
     * @param $id
     * @return CategoryEntity
     */
    public function getCategoryById($id)
    {
        return $this->getCategoryRepository()->findOneBy(array("id" => $id));
    }


    /**
     * @return array
     */
    public function getAllCategories()
    {
        return $this->getCategoryRepository()->findBy(array(), array('name' => 'ASC'));
    }

    /**
     * @param $id
     * @return Form
     */
    public function getEditCategoryForm($id)
    {
        $Category = $this->getCategoryById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgstore_category_edit_form');

        $form->populateValues($Category->toArray());

        return $form;
    }

    /**
     * @param $id
     * @return Form
     */
    public function getEditProductForm($id)
    {
        $Product = $this->getProductById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgstore_product_edit_form');

        $form->populateValues($Product->toArray());

        return $form;
    }

    /**
     * @return Form
     */
    public function getAddProductForm()
    {
        return $this->getServiceManager()->get('FormElementManager')->get('wdgstore_product_add_form');
    }

    /**
     * @return Form
     */
    public function getAddCategoryForm()
    {
        return $this->getServiceManager()->get('FormElementManager')->get('wdgstore_category_add_form');
    }
    
    public function editProductCategoriesByArray($array)
    {
        $form   = $this->getProductCategoryForm($array["id"]);
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())
            throw new FormException("Form values are invalid");
       
        $data = $form->getInputFilter()->getValues();
        
        $Product = $this->getProductById($data["id"]);
        
        if(!$Product)throw new FormException("Form values are invalid. Incorrect Product");
        
        $Product->getCategories()->clear();
        
        if(is_array($data["categories"]))
            foreach($data["categories"] as $category_id)
            {
                $Category = $this->getCategoryById($category_id);

                if(!$Category)throw new FormException("Form values are invalid. Incorrect Category");

                $Product->addCategory($Category);
            }
        
        $em->persist($Product);  
              
        $em->flush();
        
        return $Product;
    }
    
    /**
     * @param array $array
     * @return \WdgStore\Entity\Product
     * @throws FormException
     */
    public function addProductByArray(array $array)
    {
        $form   = $this->getAddProductForm();
        $em     = $this->getEntityManager();

        $form->setData($array);

        if(!$form->isValid())throw new FormException("Form values are invalid");

        $data      = $form->getInputFilter()->getValues();
        $Product   = new ProductEntity();

        $Product
            ->setPrice($data["price"])
            ->setName($data["name"])
            ->setSlug($data["slug"])
            ->setFeatured($data["featured"])
            ->setDescription($data["description"]);

        $em->persist($Product);

        $em->flush();

        return $Product;
    }
    
    /**
     * @param array $array
     * @return CategoryEntity
     * @throws FormException
     */
    public function editProductByArray($array)
    {
        $form   = $this->getEditProductForm($array["id"]);
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())throw new FormException("Form values are invalid");
        
        $data       = $form->getInputFilter()->getValues();        
        $Product    = $this->getProductById($data["id"]);
        
        $Product->setPrice($data["price"])
            ->setName($data["name"])
            ->setSlug($data["slug"])
            ->setFeatured($data["featured"])
            ->setDescription($data["description"]);
        
        $em->persist($Product);
              
        $em->flush();
        
        return $Product;
    }

    /**
     * @param array $array
     * @return \WdgStore\Entity\Category
     * @throws FormException
     */
    public function addCategoryByArray(array $array)
    {
        $form   = $this->getAddCategoryForm();
        $em     = $this->getEntityManager();

        $form->setData($array);

        if(!$form->isValid())throw new FormException("Form values are invalid");

        $data       = $form->getInputFilter()->getValues();
        $Category   = new CategoryEntity();

        $Category->setName($data["name"])
            ->setSlug($data["slug"]);

        $em->persist($Category);

        $em->flush();

        return $Category;
    }

    /**
     * @param array $array
     * @return CategoryEntity
     * @throws FormException
     */
    public function editCategoryByArray($array)
    {
        $form   = $this->getEditCategoryForm($array["id"]);
        $em     = $this->getEntityManager();

        $form->setData($array);

        if(!$form->isValid())throw new FormException("Form values are invalid");

        $data   = $form->getInputFilter()->getValues();

        $Category = $this->getCategoryById($data["id"]);

        $Category->setName($data["name"])
            ->setSlug($data["slug"]);

        $em->persist($Category);

        $em->flush();

        return $Category;
    }
    
    /**
     * @param int $id
     * @return \WdgStore\Service\Store
     * @throws \Exception
     */
    public function deleteCategory($id)
    {
        $category = $this->getCategoryById($id);
        
        if(!$category)
            throw new \Exception("Could not delete category. That category does not exist.");
        
        $em = $this->getEntityManager();
        
        $em->remove($category);
        $em->flush();
        
        return $this;
    }

    /**
     * @param string $slug
     * @param int $pageNumber
     * @param int $productsPerPage
     * @return ZendPaginator
     */
    public function getProductByCategorySlugPaginator($slug, $pageNumber, $productsPerPage)
    {
        $paginator = new ZendPaginator(
            new PaginatorAdapter( //@todo include paginator
                new ORMPaginator(
                    $this->getProductRepository()
                        ->findProductsByCategorySlugPaginationQuery($slug) //@todo write this method
                )
            )
        );

        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($productsPerPage);
    }
    
    /**
     * @param \WdgStore\Entity\Product $product
     * @return Form
     */
    public function getProductAddImageForm(ProductEntity $product)
    {
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgstore_product_add_image_form');
        
        $form->get("product_id")->setValue($product->getId());
        
        return $form;
    }    
    
    /**
     * @return Form
     */
    public function getProductCategoryForm($id)
    {
        $Product = $this->getProductById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgstore_product_category_form');
        
        $values = array("id" => $Product->getId());
        
        if($form->get("categories") instanceof \Zend\Form\Element\MultiCheckbox)
        {
            $category_values = array();
            
            foreach($Product->getCategories() as $Category)
                $category_values[] = $Category->getId();
            
            $values["categories"] = $category_values;
        }
       
        $form->populateValues($values);
        
        return $form;
    }    

    public function addProductImageByArray($data)
    {
        $product    = $this->getProductById($data["product_id"]);
        $form       = $this->getProductAddImageForm($product);
        $em         = $this->getEntityManager();

        $form->setData($data);

        if(!$product || !$form->isValid())throw new FormException("Form values are invalid");
        
        $em->beginTransaction();
        
        $tags = array();
            
        if($this->getOptions()->getImageTag())
        {
            $tags[] = $this->getOptions()->getImageTag();
        }

        /* @var $fileBank \FileBank\Manager */
        $fileBank = $this->getServiceManager()->get('FileBank');

        /* @var $file \FileBank\Entity\File */
        $file = $fileBank->save($data["image"]["tmp_name"], $tags);

        if(isset($data["image_name"]) && strlen($data["image_name"]) > 0)
            $file->setName($data["image_name"]);

        $product->addImage($file);

        $em->persist($file);
        
        $em->persist($product);  
        
        $em->commit(); 
              
        $em->flush();
        
        return $file;
    }
    
    /**
     * @param int $id
     * @param int $image_id
     * @throws Exception
     */
    public function setImageFeatured($id, $image_id)
    {
        $file_service   = $this->getFileService();
        $file           = $file_service->getFileById($image_id);
        $product        = $this->getProductById($id);
        
        if(!$product || !$file)
            throw new Exception("Could not find file or product.");
        
        $em = $this->getEntityManager();
        
        $product->setFeaturedImage($file);
        
        $em->persist($product);
        $em->flush();
    }
    
    /**
     * @param int $pageNumber
     * @param int $categoriesPerPage
     * @return ZendPaginator
     */
    public function getCategoriesPaginator($pageNumber, $categoriesPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator($this->getCategoryRepository()->findAlphaPaginationQuery())
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($categoriesPerPage);
    }
    
    /**
     * @param \WdgStore\Service\ModuleOptionsInterface $options
     */
    public function setOptions( ModuleOptionsInterface $options )
    {
        $this->options = $options;
    }

    /**
     * @return \WdgStore\Options\ModuleOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('wdgstore_module_options'));
        }
        
        return $this->options;
    }
    
    /**
     * @return \FileBank\Manager
     */
    public function getFileService()
    {
        return $this->getServiceManager()->get("FileBank");
    }
}
