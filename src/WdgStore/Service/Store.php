<?php
namespace WdgStore\Service;

use Zend\Form\Form,
    WdgZf2\Service\ServiceAbstract,
    Zend\Paginator\Paginator as ZendPaginator,
    WdgStore\Entity\Product as ProductEntity,
    WdgStore\Entity\Category as CategoryEntity;

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
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getProductRepository()
    {
        if (null === $this->postRepos)
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
        $Post = $this->getPostById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgstore_post_edit_form');

        $form->populateValues($Post->toArray());

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

}
