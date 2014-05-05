<?php
namespace WdgStore\Filter\Store\Product;

use Zend\InputFilter\InputFilter;

class Base extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Name is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 200,
                        'messages' => array(
                            'stringLengthTooLong' => 'Name is too long. 200 characters maximum'
                        )
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'slug',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Slug is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 200,
                        'messages' => array(
                            'stringLengthTooLong' => 'Slug is too long. 200 characters maximum'
                        )
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty', 
                    'break_chain_on_failure' => true,  
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Description is required'
                        ),
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100000,
                        'messages' => array(
                            'stringLengthTooLong' => 'Description is too long. 100000 characters maximum'
                        )
                    ),
                ),
            ),
        ));
    }
}

