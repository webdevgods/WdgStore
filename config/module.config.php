<?php
return array(
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
);