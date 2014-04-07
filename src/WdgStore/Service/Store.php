<?php
namespace WdgStore\Service;

use Zend\Form\Form,
    WdgZf2\Service\ServiceAbstract,
    WdgStore\Entity\Product as PostEntity,
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
    public function getPostRepository()
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

}