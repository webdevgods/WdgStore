<?php
namespace WdgStore\Filter\Store\Product;

use Zend\InputFilter\InputFilter;

class AddImage extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'image_name',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            )
        ));
        
        $this->add(array(
            'name' => 'image',
            'required' => true,
        ));
        
        $this->add(array(
            'name' => 'product_id',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            )
        ));
    }
}

