<?php
namespace WdgStore\Form\Store\Product;

use WdgZf2\Form\PostFormAbstract;

class Base extends PostFormAbstract
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => 'Slug',
            ),
        ));
        
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => 'Price',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'featured',
            'options' => array(
                'label' => 'Featured',
                'value_options' => array(
                    '0' => 'No',
                    '1' => 'Yes',
                ),
            ),
        ));
        
        $this->add(array(
            'type' => 'textarea',
            'name' => 'description',
            'options' => array(
                'label' => 'Description',
            ),
        ));
    }
}