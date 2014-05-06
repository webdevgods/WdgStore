<?php
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'WdgStore_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/WdgStore/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'WdgStore\Entity' => 'WdgStore_driver'
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        'WdgStore' => 'application/layout/layout',
    ),
    'navigation' => array(
        'admin' => array(
            'wdgstore' => array(
                'label' => 'Store',
                'route' => 'zfcadmin/wdg-store-admin'
            ),
        ),
    ),
);