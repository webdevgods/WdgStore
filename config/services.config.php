<?php
namespace WdgStore;

return array(
    'invokables' => array(
        'wdgstore_service_store' => 'WdgStore\Service\Store'
    ),
    'factories' => array(
        'wdgstore_doctrine_em' => function ($sm) {
            return $sm->get('doctrine.entitymanager.orm_default');
        },
        'wdgstore_repos_product' => function ($sm) {
            return $sm->get('doctrine.entitymanager.orm_default')
                ->getRepository("WdgStore\Entity\Product");
        },
        'wdgstore_module_options' => function ($sm) {
            $config = $sm->get('Config');
            return new Options\ModuleOptions(isset($config['wdgstore']) ? $config['wdgstore'] : array());
        },
        'wdgstore_repos_category' => function ($sm) {
            return $sm->get('doctrine.entitymanager.orm_default')
                ->getRepository("WdgStore\Entity\Category");
        }
    )
);