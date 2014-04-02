<?php
return array(
    'aliases' => array(
        'wdgstore_doctrine_em' => 'doctrine.entitymanager.orm_default',
    ),
    'invokables' => array(
        'wdgstore_service_blog' => 'WdgStore\Service\Blog'
    ),
    'factories' => array(
        'wdgstore_repos_product' => function ($sm) {
                return $sm->get('wdgstore_doctrine_em')->getRepository("WdgStore\Entity\Product");
            },
        'wdgstore_repos_category' => function ($sm) {
                return $sm->get('wdgstore_doctrine_em')->getRepository("WdgStore\Entity\Category");
            }
    )
);