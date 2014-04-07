<?php
return array(
    'router' => array(
        'routes' => array(
            'wdg-store' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/store',
                    'defaults' => array(
                        'controller' => 'WdgStore\Controller\Store',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'product' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/product[/:slug]',
                            'constraints' => array(
                                'slug' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'controller' => 'WdgStore\Controller\Store',
                                'action' => 'product'
                            )
                        ),
                    ),
                    'category' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/category[/:slug]',
                            'constraints' => array(
                                'slug' => '[a-zA-Z0-9_-]+'
                            ),
                            'defaults' => array(
                                'page' => 1,
                                'controller' => 'WdgStore\Controller\Store',
                                'action' => 'index'
                            )
                        ),
                    )
                )
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'wdg-store-admin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/store',
                            'defaults' => array(
                                'controller' => 'WdgStore\Controller\StoreAdmin',
                                'action'     => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'product' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/product'
                                ),
                                'child_routes' => array(
                                    'show' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/[:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'show'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 100,
                                    ),
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:page]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'list',
                                                'page' => '1'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'add' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/add',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'add'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'delete' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'delete'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'edit'
                                            )
                                        ),
                                        'priority' => 1000,
                                        'may_terminate' => true,
                                    ),
                                    'categories' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/categories[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminProduct',
                                                'action' => 'categories'
                                            )
                                        ),
                                        'priority' => 1000,
                                        'may_terminate' => true,
                                    ),
                                ),
                            ),
                            'category' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/category',
                                ),
                                'child_routes' => array(
                                    'show' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminCategory',
                                                'action' => 'show'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 10,
                                    ),
                                    'list' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/list',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminCategory',
                                                'action' => 'list'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'add' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/add',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminCategory',
                                                'action' => 'add'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'delete' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminCategory',
                                                'action' => 'delete'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:id]',
                                            'defaults' => array(
                                                'controller' => 'WdgStore\Controller\StoreAdminCategory',
                                                'action' => 'edit'
                                            )
                                        ),
                                        'may_terminate' => true,
                                        'priority' => 1000,
                                    ),
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);