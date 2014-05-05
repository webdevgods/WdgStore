<?php
namespace WdgStore\Form\Store\Product;

class Edit extends Base
{
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));
    }
}